<template>
    <div :class="[{'YggUpload--empty':!file, 'YggUpload--disabled':readOnly}, modifiersClasses]" class="YggUpload">
        <div class="YggUpload__inner">
            <div class="YggUpload__content">
                <form class="dropzone" v-show="!file">
                    <button :disabled="readOnly" class="dz-message YggButton YggButton--ghost YggUpload__upload-button"
                            type="button">
                        {{ l('form.upload.browse_button') }}
                    </button>
                </form>
                <template v-if="file">
                    <div :class="{ row:showThumbnail }" class="YggUpload__container">
                        <div :class="[modifiers.compacted?'col-4 col-sm-3 col-xl-2':'col-4 col-md-4']"
                             class="YggUpload__thumbnail" v-if="showThumbnail">
                            <img :src="imageSrc" @load="handleImageLoaded">
                        </div>
                        <div :class="{[modifiers.compacted?'col-8 col-sm-9 col-xl-10':'col-8 col-md-8']:showThumbnail}"
                             class="YggUpload__infos">
                            <div class="mb-3 text-truncate">
                                <label class="YggUpload__filename">{{ fileName }}</label>
                                <div class="YggUpload__info mt-2">
                                    <span class="mr-2" v-show="size">{{ size }}</span>
                                    <a @click.prevent="download" class="YggUpload__download-link" href=""
                                       v-show="canDownload">
                                        {{ l('form.upload.download_link') }}
                                    </a>
                                </div>
                                <transition name="YggUpload__progress">
                                    <div class="YggUpload__progress mt-2" v-show="inProgress">
                                        <div :aria-valuenow="progress" :style="{width:`${progress}%`}"
                                             aria-valuemax="100"
                                             aria-valuemin="0" class="YggUpload__progress-bar" role="progressbar"></div>
                                    </div>
                                </transition>
                            </div>
                            <div v-show="!readOnly">
                                <button :disabled="!isCroppable" @click="onEditButtonClick"
                                        class="YggButton YggButton--sm YggButton--secondary" type="button"
                                        v-show="!!originalImageSrc && !inProgress">
                                    {{ l('form.upload.edit_button') }}
                                </button>
                                <button :disabled="readOnly" @click="remove()"
                                        class="YggButton YggButton--sm YggButton--secondary YggButton--danger YggUpload__remove-button"
                                        type="button">
                                    {{ l('form.upload.remove_button') }}
                                </button>
                            </div>
                        </div>
                        <button @click="remove()" class="YggUpload__close-button" type="button" v-show="!readOnly">
                            <svg aria-label="close"
                                 class="YggUpload__close-icon" fill-rule="evenodd" height="10" viewBox="0 0 10 10"
                                 width="10">
                                <path
                                    d="M9.8 8.6L8.4 10 5 6.4 1.4 10 0 8.6 3.6 5 .1 1.4 1.5 0 5 3.6 8.6 0 10 1.4 6.4 5z"></path>
                            </svg>
                        </button>
                    </div>
                </template>
                <div class="clip-preview-template" ref="clip-preview-template" style="display: none;">
                    <div></div>
                </div>
            </div>
        </div>
        <template v-if="!!originalImageSrc && isCroppable">
            <ygg-modal :title="l('modals.cropper.title')" :visible.sync="showEditModal" @hidden="onEditModalHidden"
                       @ok="onEditModalOk" @shown="onEditModalShown"
                       no-close-on-backdrop ref="modal">
                <vue-cropper :aspect-ratio="ratioX/ratioY"
                             :auto-crop-area="1"
                             :background="true"
                             :guides="false"
                             :ready="onCropperReady"
                             :rotatable="true"
                             :src="originalImageSrc"
                             :view-mode="2"
                             :zoomable="false"
                             alt="Source image"
                             class="YggUpload__modal-vue-cropper"
                             drag-mode="crop"
                             ref="cropper">
                </vue-cropper>
                <div>
                    <button @click="rotate(-90)" class="YggButton YggButton--primary"><i class="fa fa-rotate-left"></i>
                    </button>
                    <button @click="rotate(90)" class="YggButton YggButton--primary"><i class="fa fa-rotate-right"></i>
                    </button>
                </div>
            </ygg-modal>
        </template>
        <a ref="dlLink" style="display: none"></a>
    </div>
</template>

<script>
    import VueClip from 'vue-clip/src/components/Clip';
    import VueCropper from 'vue-cropperjs';
    import YggModal from '../../../Modal';
    import rotateResize from './rotate';
    import {Localization} from '../../../../mixins';
    import {VueClipModifiers} from './modifiers';

    export default {
        name: 'YggVueClip',
        extends: VueClip,
        components: {
            YggModal,
            VueCropper
        },
        inject: ['actionsBus', 'axiosInstance', '$form', '$field'],
        mixins: [Localization, VueClipModifiers],
        props: {
            downloadId: String,
            pendingKey: String,
            ratioX: Number,
            ratioY: Number,
            value: Object,
            croppableFileTypes: Array,
            readOnly: Boolean
        },
        data() {
            return {
                showEditModal: false,
                croppedImg: null,
                resized: false,
                allowCrop: false,
                isNew: !this.value,
                canDownload: !!this.value,
            }
        },
        watch: {
            value(value) {
                if (!value) {
                    this.files = [];
                }
            },
            'file.status'(status) {
                (status in this.statusFunction) && this[this.statusFunction[status]]();
            },
        },
        computed: {
            file() {
                return this.files[0];
            },
            originalImageSrc() {
                return this.file && (this.file.thumbnail || this.file.dataUrl);
            },
            imageSrc() {
                return this.croppedImg || this.originalImageSrc;
            },
            size() {
                if (this.file.size == null) {
                    return '';
                }
                let size = (parseFloat((this.file.size).toFixed(2)) / 1024) / 1024;
                let res = '';
                if (size < 0.1) {
                    res += '<';
                    size = 0.1
                }
                res += size.toLocaleString();
                return `${res} MB`;
            },
            operationFinished() {
                return {
                    crop: this.hasInitialCrop ? !!this.croppedImg : null
                }
            },
            operations() {
                return Object.keys(this.operationFinished);
            },
            activeOperationsCount() {
                return this.operations.filter(op => this.operationFinished[op] !== null).length;
            },
            operationFinishedCount() {
                return this.operations.filter(op => this.operationFinished[op]).length;
            },
            progress() {
                let curProgress = this.file ? this.file.progress : 100;
                let delta = this.activeOperationsCount - this.operationFinishedCount;
                let factor = (1 - delta * .05);
                return Math.floor(curProgress) * factor;
            },
            inProgress() {
                return (this.file && this.file.status !== 'exist') && this.progress < 100;
            },
            statusFunction() {
                return {
                    error: 'onStatusError', success: 'onStatusSuccess', added: 'onStatusAdded'
                }
            },
            fileName() {
                let splitted = this.file.name.split('/');
                return splitted.length ? splitted[splitted.length - 1] : '';
            },
            fileExtension() {
                let extension = this.fileName.split('.').pop();
                return extension ? `.${extension}` : null;
            },
            downloadLink() {
                return `${this.$form.downloadLinkBase}/${this.downloadId}`;
            },
            showThumbnail() {
                return this.imageSrc;
            },
            hasInitialCrop() {
                return !!(this.ratioX && this.ratioY) && this.isCroppable;
            },
            isCroppable() {
                return !this.croppableFileTypes || this.croppableFileTypes.includes(this.fileExtension);
            }
        },
        methods: {
            setPending(value) {
                this.actionsBus.$emit('setPendingJob', {
                    key: this.pendingKey,
                    origin: 'upload',
                    value
                });
            },
            // status callbacks
            onStatusAdded() {
                this.$emit('reset');
                this.setPending(true);
            },
            onStatusError() {
                let msg = this.file.errorMessage;
                this.remove();
                this.$emit('error', msg);
            },
            onStatusSuccess() {
                let data = {};
                try {
                    data = JSON.parse(this.file.xhrResponse.responseText);
                } catch (e) {
                    console.log(e);
                }
                data.uploaded = true;
                this.$emit('success', data);
                this.$emit('input', data);
                this.setPending(false);
                this.allowCrop = true;
                this.$nextTick(_ => {
                    this.isCropperReady() && this.onCropperReady();
                });
            },
            async download() {
                if (!this.value.uploaded) {
                    let {data} = await this.axiosInstance.post(this.downloadLink, {fileName: this.value.name}, {responseType: 'blob'});
                    //console.log(data);
                    let $link = this.$refs.dlLink;
                    $link.href = URL.createObjectURL(data);
                    $link.download = this.fileName;
                    $link.click();
                }
            },
            // actions
            remove() {
                this.canDownload = false;
                this.removeFile(this.file);
                this.files.splice(0, 1);
                this.setPending(false);
                this.resetEdit();
                this.$emit('input', null);
                this.$emit('reset');
                this.$emit('removed');
            },
            resetEdit() {
                this.croppedImg = null;
                this.resized = false;
            },
            onEditButtonClick() {
                this.$emit('active');
                this.showEditModal = true;
                this.allowCrop = true;
            },
            handleImageLoaded() {
                if (this.isNew) {
                    this.$emit('image-updated');
                }
            },
            onEditModalShown() {
                if (!this.resized) {
                    this.$nextTick(() => {
                        let cropper = this.$refs.cropper.cropper;
                        cropper.resize();
                        cropper.reset();
                        this.resized = true;
                    });
                }
            },
            onEditModalHidden() {
                this.$emit('inactive');
                setTimeout(() => this.$refs.cropper.cropper.reset(), 300);
            },
            onEditModalOk() {
                this.updateCroppedImage();
                this.updateCropData();
            },
            isCropperReady() {
                return this.$refs.cropper && this.$refs.cropper.cropper.ready;
            },
            onCropperReady() {
                if (this.hasInitialCrop) {
                    this.updateCroppedImage();
                    this.updateCropData();
                }
            },
            updateCropData() {
                let cropData = this.getCropData();
                let imgData = this.getImageData();
                let rw = imgData.naturalWidth, rh = imgData.naturalHeight;
                if (Math.abs(cropData.rotate) % 180) {
                    rw = imgData.naturalHeight;
                    rh = imgData.naturalWidth;
                }
                //console.log('img', rw, rh, imgData.naturalWidth, imgData.naturalHeight);
                //console.log('crop', cropData.width, cropData.height);
                let relativeData = {
                    width: cropData.width / rw,
                    height: cropData.height / rh,
                    x: cropData.x / rw,
                    y: cropData.y / rh,
                    rotate: cropData.rotate * -1 // counterclockwise
                };
                if (this.allowCrop) {
                    let data = {
                        ...this.value,
                        cropData: relativeData,
                    };
                    this.$emit('input', data);
                    this.$emit('updated', data);
                }
            },
            updateCroppedImage() {
                if (this.allowCrop) {
                    this.isNew = true;
                    this.croppedImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
                }
            },
            getCropData() {
                return this.$refs.cropper.getData(true);
            },
            getImageData() {
                return this.$refs.cropper.getImageData();
            },
            rotate(degree) {
                rotateResize(this.$refs.cropper.cropper, degree);
            },
        },
        created() {
            this.options.thumbnailWidth = null;
            this.options.thumbnailHeight = null;
            this.options.maxFiles = 1;
            if (!this.value)
                return;
            this.addedFile({...this.value, upload: {}});
            this.file.thumbnail = this.value.thumbnail;
            this.file.status = 'exist';
        },
        beforeDestroy() {
            this.uploader._uploader.destroy();
        },
    }
</script>
