<template>
    <div :class="{
              'YggFilterSelect--open':opened,
              'YggFilterSelect--empty':empty,
              'YggFilterSelect--multiple':multiple,
              'YggFilterSelect--searchable':searchable
          }"
         class="YggFilterSelect"
         tabindex="0"
    >
        <!-- dropdown & search input -->
        <ygg-autocomplete
            class="YggFilterSelect__select"
            :value="autocompleteValue"
            :local-values="values"
            :search-keys="searchKeys"
            :list-item-template="template"
            :placeholder="l('resource_list.filter.search_placeholder')"
            :multiple="multiple"
            :hide-selected="multiple"
            :allow-empty="!required"
            :preserve-search="false"
            :show-pointer="false"
            :searchable="searchable"
            no-result-item
            mode="local"
            ref="autocomplete"
            @multiselect-input="handleAutocompleteInput"
            @close="close"
        />

        <YggFilterControl :label="label" @click="handleClick" no-caret>
            <!-- value text & tags -->
            <ygg-select
                :clearable="!required"
                :inline="false"
                :multiple="multiple"
                :options="values"
                :value="value"
                @input="handleSelect"
                class="YggFilterSelect__select"
                placeholder=" "
                ref="select"
            />
        </YggFilterControl>
    </div>
</template>

<script>
    import YggDropdown from '../../dropdown/Dropdown';
    import YggSelect from '../../form/fields/Select';
    import YggAutocomplete from '../../form/fields/Autocomplete';
    import YggFilterControl from '../FilterControl';
    import {Localization} from '../../../mixins';

    export default {
        name: 'YggFilterSelect',
        mixins: [Localization],
        components: {
            YggDropdown,
            YggSelect,
            YggAutocomplete,
            YggFilterControl,
        },
        props: {
            label: {
                type: String,
                required: true
            },
            values: {
                type: Array,
                required: true
            },
            value: {
                type: [String, Number, Array],
            },
            multiple: Boolean,
            required: Boolean,
            searchable: Boolean,
            searchKeys: Array,
            template: String,
        },
        data() {
            return {
                opened: false
            }
        },
        computed: {
            optionById() {
                return this.values.reduce((res, v)=> ({
                    ...res, [v.id]: v
                }), {});
            },
            empty() {
                return this.value == null || this.multiple && !this.value.length;
            },
            autocompleteValue() {
                return this.multiple ? (this.value||[]).map(value=>this.optionById[value]) : this.optionById[this.value];
            }
        },
        methods: {
            handleSelect(value) {
                this.$emit('input', value);
            },
            handleAutocompleteInput(value) {
                this.$emit('input', this.multiple ? value.map(v=>v.id) : (value||{}).id);
            },
            handleClick() {
                if(this.opened) {
                    this.close();
                } else {
                    this.open();
                }
            },
            open() {
                this.opened = true;
                this.$emit('open');
                this.$nextTick(this.showDropdown);
            },
            close() {
                this.opened = false;
                this.$emit('close');
                this.$nextTick(this.blur);
            },
            showDropdown() {
                let { autocomplete:{ $refs: { multiselect } } } = this.$refs;
                multiselect.activate();
            },
            blur() {
                let { select:{ $refs: { multiselect } } } = this.$refs;
                multiselect.deactivate();
            },
        }
    }
</script>
