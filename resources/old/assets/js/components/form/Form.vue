<template>
    <div class="YggForm">
        <template v-if="ready">
            <div class="container">
                <div class="YggNotification YggNotification--error" role="alert" v-show="hasErrors">
                    <div class="YggNotification__details">
                        <div class="YggNotification__text-wrapper">
                            <p class="YggNotification__title">{{ l('form.validation_error.title') }}</p>
                            <p class="YggNotification__subtitle">{{ l('form.validation_error.description') }}</p>
                        </div>
                    </div>
                </div>
                <ygg-tabbed-layout :layout="layout" ref="tabbedLayout">
                    <!-- Tab -->
                    <template slot-scope="tab">
                        <ygg-grid :rows="[tab.columns]" ref="columnsGrid">
                            <!-- column -->
                            <template slot-scope="column">
                                <ygg-fields-layout :layout="column.fields" :visible="fieldVisible" ref="fieldLayout"
                                                   v-if="fields">
                                    <!-- field -->
                                    <template slot-scope="fieldLayout">
                                        <ygg-field-display
                                            :config-identifier="fieldLayout.key"
                                            :context-data="data"
                                            :context-fields="transformedFields"
                                            :error-identifier="fieldLayout.key"
                                            :field-key="fieldLayout.key"
                                            :field-layout="fieldLayout"
                                            :locale="fieldLocale[fieldLayout.key]"
                                            :update-data="updateData"
                                            :update-visibility="updateVisibility"
                                            @locale-change="updateLocale"
                                            ref="field"
                                        />
                                    </template>
                                </ygg-fields-layout>
                            </template>
                        </ygg-grid>
                    </template>
                </ygg-tabbed-layout>
            </div>
        </template>
    </div>
</template>

<script>
    import * as util from '../../util';
    import {BASE_URL} from '../../consts';
    import {ActionEvents, Localization, ReadOnlyFields} from '../../mixins';
    import DynamicView from '../DynamicViewMixin';
    import YggTabbedLayout from '../TabbedLayout'
    import YggGrid from '../Grid';
    import YggFieldsLayout from './FieldsLayout.vue';
    // import YggLocaleSelector from '../LocaleSelector.vue';
    import localize from '../../mixins/localize/form';
    import {getDependantFieldsResetData, transformFields} from "../../util/form";

    const noop = ()=>{};
    export default {
        name:'YggForm',
        extends: DynamicView,
        mixins: [ActionEvents, ReadOnlyFields('fields'), Localization, localize('fields')],
        components: {
            YggTabbedLayout,
            YggFieldsLayout,
            YggGrid,
            // YggLocaleSelector
        },
        props:{
            resourceKey: String,
            instanceId: String,
            /// Extras props for customization
            independant: {
                type:Boolean,
                default: false
            },
            ignoreAuthorizations: Boolean,
            reloadOnSubmit: {
                type:Boolean,
                default: false
            },
            props: Object
        },
        inject:['actionsBus'],
        provide() {
            return {
                $form:this
            }
        },
        data() {
            return {
                ready: false,
                fields: null,
                authorizations: null,
                errors:{},
                fieldLocale: {},
                locales: null,
                fieldVisible: {},
                curFieldsetId:0,
                pendingJobs: []
            }
        },
        computed: {
            apiPath() {
                let path = `form/${this.resourceKey}`;
                if(this.instanceId) path+=`/${this.instanceId}`;
                return path;
            },
            localized() {
                return Array.isArray(this.locales) && !!this.locales.length;
            },
            isCreation() {
                return !this.instanceId;
            },
            isReadOnly() {
                return !this.ignoreAuthorizations && !(this.isCreation ? this.authorizations.create : this.authorizations.update);
            },
            // don't show loading on creation
            synchronous() {
                return this.independant;
            },
            hasErrors() {
                return Object.keys(this.errors).some(errorKey => !this.errors[errorKey].cleared);
            },
            baseResourceKey() {
                return this.resourceKey.split(':')[0];
            },
            downloadLinkBase() {
                return `/download/${this.resourceKey}/${this.instanceId}`;
            },
            listUrl() {
                return `${BASE_URL}/list/${this.baseResourceKey}?restore-context=1`;
            },
            localeSelectorErrors() {
                return Object.keys(this.errors).reduce((res,errorKey)=>{
                    let errorLocale = this.locales.find(l=>errorKey.endsWith(`.${l}`));
                    if(errorLocale) {
                        res[errorLocale] = true;
                    }
                    return res;
                },{})
            },
            transformedFields() {
                const fields = this.isReadOnly
                    ? this.readOnlyFields
                    : this.fields;
                return transformFields(fields, this.data);
            },
        },
        methods: {
            async updateData(key, value, {forced} = {}) {
                this.data = {
                    ...this.data,
                    ...(!forced ? getDependantFieldsResetData(this.fields, key,
                        field => this.fieldLocalizedValue(field.key, null),
                    ) : null),
                    [key]: this.fieldLocalizedValue(key, value),
                }
            },
            updateVisibility(key, visibility) {
                this.$set(this.fieldVisible, key, visibility);
            },
            updateLocale(key, locale) {
                this.$set(this.fieldLocale, key, locale);
            },
            mount({fields, layout, data={}, authorizations={}, locales,}) {
                this.fields = fields;
                this.data = data;
                this.layout = this.patchLayout(layout);
                this.locales = locales;
                this.authorizations = authorizations;
                if(fields) {
                    this.fieldVisible = Object.keys(this.fields).reduce((res, fKey) => {
                        res[fKey] = true;
                        return res;
                    },{});
                    this.fieldLocale = this.defaultFieldLocaleMap({ fields, locales });
                }
                this.validate();
            },
            validate() {
                const localizedFields = Object.keys(this.fieldLocale);
                const alert = text => this.actionsBus.$emit('showMainModal', {
                    title: 'Data error',
                    text,
                    isError: true,
                    okCloseOnly: true,
                });
                if(localizedFields.length > 0 && !this.locales.length) {
                    alert("Some fields are localized but the form hasn't any locales configured");
                }
            },
            handleError({response}) {
                if(response.status===422)
                    this.errors = response.data.errors || {};
            },
            patchLayout(layout) {
                if(!layout)return;
                let curFieldsetId = 0;
                let mapFields = layout => {
                    if(layout.legend)
                        layout.id = `${curFieldsetId++}#${layout.legend}`;
                    else if(layout.fields)
                        layout.fields.forEach(row => {
                            row.forEach(mapFields);
                        });
                };
                layout.tabs.forEach(t => t.columns.forEach(mapFields));
                return layout;
            },
            async init() {
                if(this.independant) {
                    this.mount(this.props);
                    this.ready = true;
                }
                else {
                    if(this.resourceKey) {
                        await this.get();
                        this.setupActionBar();
                        this.ready = true;
                    }
                    else util.error('no resource key provided');
                }
            },
            setupActionBar({ disable=false }={}) {
                const showSubmitButton = this.isCreation ? this.authorizations.create : this.authorizations.update;
                this.actionsBus.$emit('setup', {
                    showSubmitButton: showSubmitButton && !disable,
                    showDeleteButton: !this.isCreation && this.authorizations.delete && !disable,
                    showBackButton: this.isReadOnly,
                    opType: this.isCreation ? 'create' : 'update'
                });
            },
            afterSubmit() {
                if(this.reloadOnSubmit) {
                    location.reload();
                    return;
                }
                location.href = this.listUrl;
            },
            async submit({ postFn }={}) {
                if(this.pendingJobs.length) {
                    return;
                }
                try {
                    const response = postFn ? await postFn(this.data) : await this.post();
                    if(this.independant) {
                        this.$emit('submit', response);
                        return response;
                    }
                    else if(response.data.ok) {
                        this.mainLoading.$emit('show');
                        this.afterSubmit();
                    }
                }
                catch(error) {
                    this.handleError(error);
                    return Promise.reject(error);
                }
            }
        },
        actions: {
            submit() {
                this.submit().catch(()=>{});
            },
            async 'delete'() {
                try {
                    await this.axiosInstance.delete(this.apiPath);
                    this.afterSubmit();
                }
                catch(error) {
                }
            },
            cancel() {
                this.afterSubmit();
            },
            setPendingJob({ key, origin, value:isPending }) {
                if(isPending)
                    this.pendingJobs.push(key);
                else
                    this.pendingJobs = this.pendingJobs.filter(jobKey => jobKey !== key);
                if(this.pendingJobs.length) {
                    this.actionsBus.$emit('updateActionsState', {
                        state: 'pending',
                        modifier: origin
                    })
                }
                else {
                    this.actionsBus.$emit('updateActionsState', null);
                }
            }
        },
        created() {
            this.$on('error-cleared', errorId => {
                if(this.errors[errorId])
                    this.$set(this.errors[errorId],'cleared',true);
            })
        },
        mounted() {
            this.init();
        }
    }
</script>
