<template>
    <ygg-vue-clip
        :croppable-file-types="croppableFileTypes"
        :download-id="downloadId"
        :modifiers="modifiers"
        :on-added-file="handleAdded"
        :options="options"
        :pending-key="pendingKey"
        :ratioX="ratioX"
        :ratioY="ratioY"
        :value="value"
        @active="$emit('active')"
        @image-updated="$emit('refresh')"
        @inactive="$emit('inactive')"
        @removed="$emit('remove')"
        @success="$emit('success',$event)"
        @updated="$emit('update', $event)"
        class="YggMarkdownUpload"
        ref="vueclip"
        v-show="show"
    />
</template>

<script>
    import Vue from 'vue';
    import YggVueClip from '../upload/VueClip';

    import {UPLOAD_URL} from '../../../../consts';
    import {UploadXSRF} from '../../../../mixins';
    import {UploadModifiers} from '../upload/modifiers';
    import {lang} from '../../../../mixins/Localization';

    const removeKeys = ['Backspace', 'Enter'];
    const escapeKeys = ['ArrowLeft', 'ArrowUp', 'ArrowDown', 'ArrowRight', 'Escape', 'Tab'];

    export default Vue.extend({

        mixins: [UploadXSRF, UploadModifiers],

        inject: ['xsrfToken'],

        props: {
            downloadId: String,
            pendingKey: String,

            id: Number,
            value: Object,

            maxImageSize: Number,
            ratioX: Number,
            ratioY: Number,
            croppableFileTypes: Array,
        },
        components: {
            YggVueClip
        },
        data() {
            return {
                show: this.value,
                marker: null
            }
        },
        computed: {
            options() {
                return this.patchXsrf({
                    url: UPLOAD_URL,
                    uploadMultiple: false,
                    acceptedFiles: {
                        extensions: ['image/*'],
                        message: lang('form.upload.message.bad_extension')
                    },
                    maxFilesize: {
                        limit: this.maxImageSize,
                        message: lang('form.upload.message.file_too_big')
                    }
                });
            },
            dropzone() {
                return this.$refs.vueclip.uploader._uploader;
            },
            fileInput() {
                return this.dropzone.hiddenFileInput;
            }
        },
        methods: {
            handleAdded() {
                this.show = true;
                this.$emit('added');
            },
            inputClick() {
                this.fileInput.click();
            },
        },
        mounted() {
            this.$on('delete-intent', () => {
                let removeButton = this.$el.querySelector('.YggUpload__remove-button');
                removeButton.focus();
                removeButton.addEventListener('keydown', e => {
                    if (removeKeys.includes(e.key)) {
                        this.$emit('remove');
                    } else if (escapeKeys.includes(e.key)) {
                        this.$emit('escape');
                    }
                })
            })
        }
    });
</script>
