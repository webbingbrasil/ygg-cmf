<template>
    <ygg-vue-clip
        :croppable-file-types="croppableFileTypes"
        :download-id="fieldConfigIdentifier"
        :modifiers="modifiers"
        :options="options"
        :pending-key="uniqueIdentifier"
        :ratioX="ratioX"
        :ratioY="ratioY"
        :read-only="readOnly"
        :value="value"
        @error="$field.$emit('error',$event)"
        @input="$emit('input',$event)"
        @reset="$field.$emit('clear')"
    />
</template>

<script>
    import YggVueClip from './VueClip';
    import {UploadModifiers} from './modifiers';

    import {UPLOAD_URL} from '../../../../consts';
    import {UploadXSRF} from '../../../../mixins';
    import {lang} from '../../../../mixins/Localization';

    export default {
        name: 'YggUpload',
        components: {
            YggVueClip
        },

        mixins: [UploadXSRF, UploadModifiers],
        inject: ['$field', 'xsrfToken'],

        props: {
            uniqueIdentifier: String,
            fieldConfigIdentifier: String,
            value: Object,

            fileFilter: Array,
            maxFileSize: Number,
            thumbnail: String,
            croppableFileTypes: Array,

            ratioX: Number,
            ratioY: Number,

            readOnly: Boolean
        },
        computed: {
            options() {
                let opt = {};

                opt.url = UPLOAD_URL;
                opt.uploadMultiple = false;

                if (this.fileFilter) {
                    opt.acceptedFiles = {
                        extensions: this.fileFilter,
                        message: lang('form.upload.message.bad_extension')
                    }
                }
                if (this.maxFileSize) {
                    opt.maxFilesize = {
                        limit: this.maxFileSize,
                        message: lang('form.upload.message.file_too_big')
                    }
                }
                this.patchXsrf(opt);
                return opt;
            }
        },
        methods: {}
    };
</script>
