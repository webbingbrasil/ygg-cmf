<template>
    <ygg-multiselect
        :close-on-select="false"
        :custom-label="localizedCustomLabel"
        :disabled="readOnly"
        :max="maxTagCount"
        :options="indexedOptions"
        :placeholder="dynamicPlaceholder"
        :show-labels="false"
        :tag-placeholder="createText"
        :taggable="creatable"
        :value="tags"
        @input="handleInput"
        @search-change="handleTextInput"
        @tag="handleNewTag" class="YggTags" hide-selected
        label="label"
        multiple
        ref="multiselect"
        searchable
        track-by="_internalId">
    </ygg-multiselect>
</template>

<script>
    import YggMultiselect from '../../Multiselect';
    import localize from '../../../mixins/localize/Tags';

    class LabelledItem {
        constructor(item) {
            this.id = item.id;
            this.label = item.label;
        }

        set internalId(id) {
            this._internalId = id;
        }

        get internalId() {
            return this._internalId;
        }
    }

    class Option extends LabelledItem {
    }

    class Tag extends LabelledItem {
    }

    export default {
        name: 'YggTags',
        mixins: [localize],
        components: {
            YggMultiselect
        },
        props: {
            value: Array, // [{id:0, label: 'AAA'}, ...]
            options: Array, // [{id:0, label:'AAA'}, ...]
            placeholder: String,
            maxTagCount: Number,
            createText: String,
            creatable: {
                type: Boolean,
                default: true
            },
            readOnly: Boolean,
        },
        data() {
            return {
                tags: [],
                lastIndex: 0
            }
        },
        computed: {
            indexedOptions() {
                return this.options.map(this.patchOption);
            },
            dynamicPlaceholder() {
                return this.tags.length < (this.maxTagCount || Infinity) ? this.placeholder : "";
            },
            ids() {
                return this.tags.map(t => t.internalId);
            }
        },
        watch: {
            tags: 'onTagsChanged'
        },
        methods: {
            patchOption(option, index) {
                let patchedOption = new Option(option);
                patchedOption.internalId = index;
                return patchedOption;
            },
            patchTag(tag) {
                let matchedOption = this.indexedOptions.find(o => o.id === tag.id);
                let patchedTag = new Tag(matchedOption);
                patchedTag.internalId = matchedOption.internalId;
                return patchedTag;
            },
            handleNewTag(val) {
                let newTag = new Tag({id: null, label: this.localizedTagLabel(val)});
                newTag.internalId = this.lastIndex++;
                this.tags.push(newTag);
            },
            handleInput(val) {
                this.tags = val;
            },
            handleTextInput(text) {
                if (text.length > 0 && this.$refs.multiselect.filteredOptions.length > 1) {
                    this.$refs.multiselect.pointer = 1;
                } else this.$refs.multiselect.pointer = 0
            },
            onTagsChanged() {
                this.$emit('input', this.tags.map(t => new Tag(t)));
            }
        },
        created() {
            this.lastIndex += this.options.length;
            this.tags = (this.value || []).map(this.patchTag);
        }
    }
</script>
