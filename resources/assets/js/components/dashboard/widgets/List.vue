<template>
    <div class="YggWidgetPanel">
        <YggDataList
                :columns="columns"
                :dir="sortDir"
                :items="items"
                :page="page"
                :page-size="pageSize"
                :paginated="paginated"
                :reorder-active="reorderActive"
                :sort="sortedBy"
                :total-count="totalCount"
                @change="handleReorderedItemsChanged"
                @page-change="handlePageChanged"
                @sort-change="handleSortChanged"
        >
            <template slot="empty">
                {{ l('resource_list.empty_text') }}
            </template>
            <template slot="item" slot-scope="{ item }">
                <YggDataListRow :columns="columns" :row="item" :url="instanceFormUrl(item)">
                    <template v-if="hasActionsColumn">
                        <template slot="append">
                            <div class="row justify-content-end justify-content-md-start mx-n2">
                                <template v-if="instanceHasState(item)">
                                    <div class="col-auto my-1 px-2">
                                        <YggDropdown :disabled="!instanceHasStateAuthorization(item)"
                                                     class="YggResourceList__state-dropdown">
                                            <template slot="text">
                                                <YggStateIcon :color="instanceStateIconColor(item)"/>
                                                <span class="text-truncate">
                                                    {{ instanceStateLabel(item) }}
                                                </span>
                                            </template>
                                            <YggDropdownItem
                                                    :key="stateOptions.value"
                                                    @click="handleInstanceStateChanged(item, stateOptions.value)"
                                                    v-for="stateOptions in config.state.values"
                                            >
                                                <YggStateIcon :color="stateOptions.color"/>&nbsp;
                                                {{ stateOptions.label }}
                                            </YggDropdownItem>
                                        </YggDropdown>
                                    </div>
                                </template>
                                <template v-if="instanceHasActions(item)">
                                    <div class="col-auto my-1 px-2">
                                        <YggActionsDropdown
                                                :actions="instanceActions(item)"
                                                @select="handleInstanceActionRequested(item, $event)"
                                                class="YggResourceList__actions-dropdown"
                                        >
                                            <template slot="text">
                                                {{ l('resource_list.actions.instance.label') }}
                                            </template>
                                        </YggActionsDropdown>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>
                </YggDataListRow>
            </template>
        </YggDataList>
        <YggActionFormModal :form="actionCurrentForm" ref="actionForm" />
        <YggActionViewPanel :content="actionViewContent" @close="handleActionViewPanelClosed" />
    </div>
</template>

<script>
    import YggDataList from '../../list/DataList.vue';
    import YggDataListRow from '../../list/DataListRow.vue';
    import YggStateIcon from '../../list/StateIcon.vue';
    import YggActionsDropdown from '../../actions/ActionsDropdown.vue';
    import YggActionFormModal from '../../actions/ActionFormModal.vue';
    import YggActionViewPanel from '../../actions/ActionViewPanel.vue';
    import {YggDropdown, YggDropdownItem} from "../../ui";
    import {Localization} from '../../../mixins';
    import DynamicViewMixin from '../../DynamicViewMixin';
    import withActions from '../../../mixins/page/with-actions';
    import {BASE_URL} from "../../../consts";
    import {mapGetters, mapState} from 'vuex';

    export default {
        name:'YggWidgetList',
        mixins: [DynamicViewMixin, Localization, withActions],
        components: {
            YggDataList,
            YggDataListRow,
            YggStateIcon,
            YggActionsDropdown,
            YggDropdown,
            YggDropdownItem,
            YggActionFormModal,
            YggActionViewPanel,
        },
        data() {
            return {
                ready: false,
                page: 0,
                search: '',
                sortedBy: null,
                sortDir: null,
                sortDirs: {},
                reorderActive: false,
                reorderedItems: null,
                fields: null,
                layout: null,
                data: null,
                config: null,
                authorizations: null,
                forms: null,
            }
        },
        props: {
            resourceKey: String,
        },
        computed: {
            ...mapGetters('resource-list', {
                filters: 'filters/filters',
                filtersValues: 'filters/values',
                filterNextQuery: 'filters/nextQuery',
                getFiltersValuesFromQuery: 'filters/getValuesFromQuery',
            }),
            hasMultiforms() {
                return !!this.forms;
            },
            apiParams() {
                return this.$route.query;
            },
            apiPath() {
                return `list/${this.resourceKey}`;
            },
            /**
             * Action bar computed data
             */
            allowedResourceActions() {
                return (this.config.actions.resource || [])
                    .map(group => group.filter(action => action.authorization))
            },
            multiforms() {
                return this.forms ? Object.values(this.forms) : null;
            },
            canCreate() {
                return !!this.authorizations.create;
            },
            canReorder() {
                return this.config.reorderable
                    && this.authorizations.update
                    && this.data.items.length > 1;
            },
            canSearch() {
                return !!this.config.searchable;
            },
            /**
             * Data list props
             */
            items() {
                return this.data.items || [];
            },
            columns() {
                return this.layout.map(columnLayout => ({
                    ...columnLayout,
                    ...this.fields[columnLayout.key]
                }));
            },
            paginated() {
                return !!this.config.paginated;
            },
            totalCount() {
                return this.data.totalCount || this.items.length;
            },
            pageSize() {
                return this.data.pageSize;
            },
            hasActionsColumn() {
                return this.items.some(instance =>
                    this.instanceHasState(instance) ||
                    this.instanceHasActions(instance)
                );
            },
        },
        methods: {
            /**
             * [Action bar] events
             */
            handleSearchChanged(search) {
                this.search = search;
            },
            handleSearchSubmitted() {
                this.$router.push({
                    query: {
                        ...this.$route.query,
                        search: this.search,
                        page: 1,
                    }
                });
            },
            handleFilterChanged(filter, value) {
                this.$router.push({
                    query: {
                        ...this.$route.query,
                        ...this.filterNextQuery({ filter, value }),
                        page: 1,
                    }
                });
            },
            handleReorderButtonClicked() {
                this.reorderActive = !this.reorderActive;
                this.reorderedItems = this.reorderActive ? [ ...this.data.items ] : null;
            },
            handleReorderSubmitted() {
                return this.$store.dispatch('resource-list/reorder', {
                    instances: this.reorderedItems.map(item => this.instanceId(item))
                }).then(() => {
                    this.$set(this.data, 'items', [ ...this.reorderedItems ]);
                    this.reorderedItems = null;
                    this.reorderActive = false;
                });
            },
            handleResourceActionRequested(action) {
                this.handleActionRequested(action, {
                    endpoint: this.actionEndpoint(action.key),
                });
            },
            handleCreateButtonClicked(multiform) {
                const formUrl = multiform
                    ? this.formUrl({ formKey:multiform.key })
                    : this.formUrl();
                location.href = formUrl;
            },
            /**
             * [Data list] getters
             */
            instanceId(instance) {
                const idAttribute = this.config.instanceIdAttribute;
                return idAttribute ? instance[idAttribute] : instance.id;
            },
            instanceState(instance) {
                if(!this.instanceHasState(instance)) {
                    return null;
                }
                const stateAttribute = this.config.state.attribute;
                return stateAttribute ? instance[stateAttribute] : instance.state;
            },
            instanceHasState(instance) {
                return !!this.config.state;
            },
            instanceHasActions(instance) {
                const allActions = this.instanceActions(instance).flat();
                return allActions.length > 0;
            },
            instanceHasStateAuthorization(instance) {
                if(!this.instanceHasState(instance)) {
                    return false;
                }
                const { authorization } = this.config.state;
                const instanceId = this.instanceId(instance);
                return Array.isArray(authorization)
                    ? authorization.includes(instanceId)
                    : !!authorization;
            },
            instanceActions(instance) {
                const allInstanceActions = this.config.actions.instance || [];
                const instanceId = this.instanceId(instance);
                return allInstanceActions.reduce((res, group) => [
                    ...res, group.filter(action => action.authorization.includes(instanceId))
                ], []);
            },
            instanceStateIconColor(instance) {
                const state = this.instanceState(instance);
                const stateOptions = this.instanceStateOptions(state);
                return stateOptions.color;
            },
            instanceStateLabel(instance) {
                const state = this.instanceState(instance);
                const stateOptions = this.instanceStateOptions(state);
                return stateOptions.label;
            },
            instanceStateOptions(instanceState) {
                if(!this.config.state) {
                    return null;
                }
                return this.config.state.values.find(stateValue => stateValue.value === instanceState);
            },
            instanceForm(instance) {
                const instanceId = this.instanceId(instance);
                return this.multiforms.find(form => form.instances.includes(instanceId));
            },
            instanceFormUrl(instance) {
                const instanceId = this.instanceId(instance);
                if(!this.instanceHasViewAuthorization(instance)) {
                    return null;
                }
                if(this.hasMultiforms) {
                    const form = this.instanceForm(instance) || {};
                    return this.formUrl({ formKey:form.key, instanceId });
                }
                return this.formUrl({ instanceId });
            },
            instanceHasViewAuthorization(instance) {
                const instanceId = this.instanceId(instance);
                const viewAuthorizations = this.authorizations.view;
                return Array.isArray(viewAuthorizations)
                    ? viewAuthorizations.includes(instanceId)
                    : !!viewAuthorizations;
            },
            filterByKey(key) {
                return (this.config.filters || []).find(filter => filter.key === key);
            },
            /**
             * [Data list] actions
             */
            async setState(instance, state) {
                const instanceId = this.instanceId(instance);
                const confirmation = this.config.state.confirmation;
                if(confirmation) {
                    await new Promise(resolve => {
                        this.actionsBus.$emit('showMainModal', {
                            title: this.l('modals.action.confirm.title'),
                            text: confirmation,
                            okCallback: resolve,
                        });
                    });
                }
                return this.axiosInstance.post(`${this.apiPath}/state/${instanceId}`, {
                    attribute: this.config.state.attribute,
                    value: state
                })
                    .then(response => {
                        const {data} = response;
                        this.handleActionRequestedResponse(data.action, data);
                    })
                    .catch(error => {
                        const {data} = error.response;
                        if (error.response.status === 422) {
                            this.actionsBus.$emit('showMainModal', {
                                title: this.l('modals.state.422.title'),
                                text: data.message,
                                isError: true,
                                okCloseOnly: true
                            });
                        }
                    })
            },
            /**
             * [Data list] events
             */
            handleInstanceStateChanged(instance, state) {
                this.setState(instance, state);
            },
            handleInstanceActionRequested(instance, action) {
                const instanceId = this.instanceId(instance);
                this.handleActionRequested(action, {
                    endpoint: this.actionEndpoint(action.key, instanceId),
                });
            },
            handleSortChanged({ prop, dir }) {
                this.$router.push({
                    query: {
                        ...this.$route.query,
                        page: 1,
                        sort: prop,
                        dir: dir,
                    }
                });
            },
            handleReorderedItemsChanged(items) {
                this.reorderedItems = items;
            },
            handlePageChanged(page) {
                this.$router.push({
                    query: {
                        ...this.$route.query,
                        page
                    }
                });
            },
            /**
             * Helpers
             */
            formUrl({ formKey, instanceId }={}) {
                return `${BASE_URL}/form/${this.resourceKey}${formKey?`:${formKey}`:''}${instanceId?`/${instanceId}`:''}`
            },
            tryParseNumber(val) {
                if(Array.isArray(val)) {
                    return val.map(this.tryParseNumber);
                }
                let n = Number(val);
                return isNaN(Number(n)) ? val : n;
            },
            filterValueOrDefault(val, filter) {
                return val != null && val !== '' ? this.tryParseNumber(val) : (filter.default || (filter.multiple?[]:null));
            },
            /**
             * Actions
             */
            initActions() {
                this.addActionHandlers({
                    'refresh': this.handleRefreshAction
                });
            },
            handleActionRequested(action, { endpoint }) {
                const query = this.$route.query;
                this.sendAction(action, {
                    postAction: () => this.axiosInstance.post(endpoint, { query }, { responseType:'blob' }),
                    postForm: data => this.axiosInstance.post(endpoint, { query, data }, { responseType:'blob' }),
                    getFormData: () => this.axiosInstance.get(`${endpoint}/data`, { params:query }).then(response => response.data.data),
                });
            },
            handleRefreshAction(data) {
                const findInstance = (list, instance) => list.find(item => this.instanceId(instance) === this.instanceId(item));
                this.data.items = this.data.items.map(item =>
                    findInstance(data.items, item) || item
                );
            },
            actionEndpoint(actionKey, instanceId) {
                return `${this.apiPath}/action/${actionKey}${instanceId?`/${instanceId}`:''}`;
            },
            /**
             * Data
             */
            mount({ fields, layout, data={}, config={}, authorizations, forms }) {
                this.fields = fields;
                this.layout = layout;
                this.data = data;
                this.config = config;
                this.authorizations = authorizations;
                this.forms = forms;
                this.config.actions = config.actions || {};
                this.config.filters = config.filters || [];
                this.page = this.data.page;
                !this.sortDir && (this.sortDir = this.config.defaultSortDir);
                !this.sortedBy && (this.sortedBy = this.config.defaultSort);
            },
            bindParams(params) {
                let { search, page, sort, dir } = params;
                this.search = search;
                page && (this.page = Number(page));
                sort && (this.sortedBy = sort);
                dir && (this.sortDir = dir);
            },
            async init() {
                // legacy
                await this.get();
                this.bindParams(this.$route.query);
                this.ready = true;
            },
        },
        beforeMount() {
            this.init();
            this.initActions();
        },
    }
</script>