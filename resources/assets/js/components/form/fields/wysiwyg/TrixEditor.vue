<template>
    <div :class="{ 'YggTrix--read-only': readOnly }" class="YggTrix">
        <div class="YggModule__inner">
            <input :id="inputId" :value="text" type="hidden">
            <trix-toolbar :id="toolbarId" class="YggModule__header" v-if="toolbar">
                <trix-custom-toolbar :toolbar="toolbar"/>
            </trix-toolbar>
            <trix-editor :input="inputId"
                         :placeholder="placeholder"
                         :style="{ height: `${height}px`, maxHeight:`${height}px` }"
                         :toolbar="toolbarId"
                         @trix-change="handleChanged"
                         class="YggModule__content"
                         ref="trix"
            ></trix-editor>
        </div>
    </div>
</template>

<script>
    import Trix from 'trix';

    import TrixCustomToolbar from './TrixCustomToolbar.vue';

    import localize from '../../../../mixins/localize/editor';

    export default {
        name: 'YggTrix',

        mixins: [localize({textProp: 'text'})],

        components: {
            TrixCustomToolbar
        },
        props: {
            value: Object,
            toolbar: Array,
            height: {
                type: Number,
                default: 250
            },
            placeholder: String,
            readOnly: Boolean,
            uniqueIdentifier: String,

        },
        watch: {
            locale() {
                this.localized && this.$refs.trix.editor.loadHTML(this.text);
            }
        },
        computed: {
            inputId() {
                return `trix-input-${this.uniqueIdentifier}`;
            },
            toolbarId() {
                return `trix-toolbar-${this.uniqueIdentifier}`;
            },
            text() {
                return this.localized ? this.localizedText : this.value.text;
            }
        },
        methods: {
            handleChanged(event) {
                this.$emit('input', this.localizedValue(event.target.value));
            }
        },
        created() {
            Trix.config.toolbar.getDefaultHTML = () => '';
        }
    }
</script>
