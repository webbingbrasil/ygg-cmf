export const UploadModifiers = {
    props: {
        compactThumbnail: Boolean
    },
    computed: {
        modifiers() {
            return {
                compacted: this.compactThumbnail
            }
        }
    }
};

export const VueClipModifiers = {
    props: {
        modifiers: {
            type: Object,
            default: () => {
            }
        }
    },
    computed: {
        modifiersClasses() {
            return {
                'YggUpload--compacted': this.modifiers.compacted
            }
        }
    }
};
