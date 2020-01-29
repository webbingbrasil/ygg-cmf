<template>
    <div class="YggSelect" :class="[{'YggSelect--multiple':multiple}, `YggSelect--${display}`]">
        <ygg-multiselect
            :allow-empty="clearable"
            :close-on-select="!multiple"
            :custom-label="multiselectLabel"
            :disabled="readOnly"
            :hide-selected="multiple"
            :max="maxSelected"
            :multiple="multiple"
            :options="multiselectOptions"
            :placeholder="placeholder"
            :searchable="false"
            :value="value"
            @close="$emit('close')"
            @input="handleInput"
            @open="$emit('open')"
            ref="multiselect"
            v-if="display==='dropdown'">
            <template v-if="clearable && !multiple && value!=null">
                <button slot="caret" class="YggSelect__clear-button" type="button" @mousedown.stop.prevent="remove()">
                    <svg class="YggSelect__clear-button-icon"
                         aria-label="close" width="10" height="10" viewBox="0 0 10 10" fill-rule="evenodd">
                        <path d="M9.8 8.6L8.4 10 5 6.4 1.4 10 0 8.6 3.6 5 .1 1.4 1.5 0 5 3.6 8.6 0 10 1.4 6.4 5z"></path>
                    </svg>
                </button>
            </template>
            <template slot="tag" slot-scope="{ option, remove }">
                <span class="multiselect__tag" :key="option">
                    <span>{{ multiselectLabel(option) }}</span>
                    <i aria-hidden="true" tabindex="1" @keypress.enter.prevent="remove(option)" @mousedown.prevent.stop="remove(option)" class="multiselect__tag-icon"></i>
                </span>
            </template>
            <slot name="option" slot="option"></slot>
        </ygg-multiselect>
        <template v-else>
            <template v-if="multiple">
                <component :is="inline?'span':'div'"
                           v-for="option in options"
                           :key="option.id"
                           class="YggSelect__item"
                           :class="{'YggSelect__item--inline':inline}"
                >
                    <ygg-check :value="checked(option.id)"
                                 :text="optionsLabel[option.id]"
                                 :read-only="readOnly"
                                 @input="handleCheckboxChanged($event,option.id)">
                    </ygg-check>
                </component>
            </template>
            <div v-else class="YggSelect__radio-button-group" :class="{'YggSelect__radio-button-group--block':!inline}">
                <component :is="inline?'span':'div'"
                           v-for="(option, index) in options"
                           :key="option.id"
                           class="YggSelect__item"
                           :class="{'YggSelect__item--inline':inline}"
                >
                    <input type="radio"
                           class="YggRadio"
                           tabindex="0"
                           :id="`${uniqueIdentifier}${index}`"
                           :checked="value===option.id"
                           :value="option.id"
                           :disabled="readOnly"
                           :name="uniqueIdentifier"
                           @change="handleRadioChanged(option.id)"
                    >
                    <label class="YggRadio__label" :for="`${uniqueIdentifier}${index}`">
                        <span class="YggRadio__appearance"></span>
                        {{ optionsLabel[option.id] }}
                    </label>

                </component>
            </div>
        </template>
    </div>
</template>

<script>
    import YggMultiselect from '../../Multiselect';
    import YggCheck from './Check.vue';
    import localize from '../../../mixins/localize/Select';
    import {setDefaultValue} from "../../../util/field";

    export default {
        name: 'YggSelect',
        mixins: [localize],
        components: {
            YggMultiselect,
            YggCheck
        },
        props: {
            value: [Array, String, Number],
            uniqueIdentifier: String,
            options: {
                type: Array,
                required: true,
                default: () => [],
            },
            multiple: {
                type: Boolean,
                default: false
            },
            display: {
                type: String,
                default: 'dropdown'
            },
            clearable: {
                type: Boolean,
                default: false
            },
            placeholder: {
                type: String,
                default: '-'
            },
            maxSelected: Number,
            readOnly: Boolean,
            inline: {
                type: Boolean,
                default: true
            },
        },
        data() {
            return {
                checkboxes: this.value
            }
        },
        watch: {
            options() {
                this.init();
            }
        },
        computed: {
            multiselectOptions() {
                return this.options.map(o => o.id);
            },
            optionsLabel() {
                // if (this.display !== 'dropdown')
                //     return;
                return this.options.reduce((map, opt) => {
                    map[opt.id] = this.localizedOptionLabel(opt);
                    return map;
                }, {});
            }
        },
        methods: {
            remove() {
                this.$emit('input', null);
            },
            multiselectLabel(id) {
                return this.optionsLabel[id];
            },
            handleInput(val) {
                this.$emit('input', val);
            },
            checked(optId) {
                return this.value.indexOf(optId) !== -1;
            },
            handleCheckboxChanged(checked, optId) {
                let newValue = this.value;
                if (checked)
                    newValue.push(optId);
                else
                    newValue = this.value.filter(val => val !== optId);
                this.$emit('input', newValue);
            },
            handleRadioChanged(optId) {
                this.$emit('input', optId);
            },
            setDefault() {
                if (!this.clearable && this.value == null && this.options.length > 0) {
                    this.$emit('input', this.options[0].id, {force: true});
                }
            },
            init() {
                setDefaultValue(this, this.setDefault, {
                    dependantAttributes: ['options'],
                });
            }
        },
        created() {
            this.init();
        }
    }
</script>
