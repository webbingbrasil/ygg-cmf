<template>
    <div :class="formGroupClasses" :style="extraStyle" class="YggFieldContainer YggForm__form-item">
        <div class="row">
            <div class="col">
                <label @click="triggerFocus" class="YggForm__label" v-if="showLabel">
                    {{label}}
                </label>
            </div>
            <template v-if="fieldProps.localized">
                <div class="col-auto">
                    <YggFieldLocaleSelector
                        :current-locale="locale"
                        :field-value="resolvedOriginalValue"
                        :is-locale-object="isLocaleObject"
                        :locales="$form.locales"
                        @change="handleLocaleChanged"
                    />
                </div>
            </template>
        </div>
        <ygg-field @blur="handleBlur"
                   @clear="clear"
                   @error="setError"
                   @ok="setOk"
                   ref="field"
                   v-bind="exposedProps">
        </ygg-field>
        <div class="YggForm__form-requirement">{{stateMessage}}</div>
        <small class="YggForm__help-message">{{helpMessage}}</small>
    </div>
</template>

<script>
    import YggField from './Field';
    import YggFieldLocaleSelector from './FieldLocaleSelector';
    import {ConfigNode, ErrorNode} from '../../mixins/index';
    import {isLocalizableValueField, resolveTextValue} from '../../mixins/localize/utils';
    import * as util from '../../util';

    export default {
        name: 'YggFieldContainer',
        mixins: [ErrorNode, ConfigNode],
        components: {
            YggField,
            YggFieldLocaleSelector,
        },
        inject: ['$tab', '$form'],
        props: {
            ...YggField.props,
            label: String,
            helpMessage: String,
            originalValue: [String, Number, Boolean, Object, Array],
        },
        data() {
            return {
                state: 'classic',
                stateMessage: ''
            }
        },
        watch: {
            value() {
                if (this.state === 'error')
                    this.clear();
            },
            '$form.errors'(errors) {
                this.updateError(errors);
            },
            locale() {
                this.updateError(this.$form.errors);
            }
        },
        computed: {
            formGroupClasses() {
                return [
                    `YggForm__form-item--type-${this.fieldType}`,
                    {
                        'YggForm__form-item--danger': this.state === 'error',
                        'YggForm__form-item--success': this.state === 'ok',
                        'YggForm__form-item--no-label': !this.showLabel,
                    }
                ];
            },
            extraStyle() {
                return this.fieldProps.extraStyle;
            },
            exposedProps() {
                return {
                    ...this.$props,
                    uniqueIdentifier: this.mergedErrorIdentifier,
                    fieldConfigIdentifier: this.mergedConfigIdentifier
                };
            },
            showLabel() {
                return !!this.label || this.label === '';
            },
            resolvedOriginalValue() {
                return resolveTextValue({field: this.fieldProps, value: this.originalValue});
            },
            isLocaleObject() {
                return isLocalizableValueField(this.fieldProps);
            }
        },
        methods: {
            updateError(errors) {
                let error = errors[this.mergedErrorIdentifier];
                if (error == null) {
                    this.clear();
                } else if (Array.isArray(error)) {
                    this.setError(error[0]);
                } else {
                    util.error(`FieldContainer : Not processable error "${this.mergedErrorIdentifier}" : `, error);
                }
            },
            setError(error) {
                this.state = 'error';
                this.stateMessage = error;
                if (this.$tab) {
                    this.$tab.$emit('error', this.mergedErrorIdentifier);
                }
            },
            setOk() {
                this.state = 'ok';
                this.stateMessage = '';
            },
            clear() {
                this.state = 'classic';
                this.stateMessage = '';
                if (this.$tab) {
                    this.$tab.$emit('clear', this.mergedErrorIdentifier);
                }
                this.$form.$emit('error-cleared', this.mergedErrorIdentifier);
            },
            triggerFocus() {
                this.$set(this.fieldProps, 'focused', true);
            },
            handleBlur() {
                this.$set(this.fieldProps, 'focused', false);
            },
            handleLocaleChanged(locale) {
                this.$emit('locale-change', this.fieldKey, locale);
            }
        },
        mounted() {
            //console.log(this);
        }
    }
</script>
