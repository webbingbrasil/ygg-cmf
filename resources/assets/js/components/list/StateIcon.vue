<template>
    <span class="StateIcon" :class="className" :style="style"></span>
</template>

<script>
    export default {
        name: 'YggStateIcon',
        props: {
            color: {
                required: true,
                type: String,
            },
        },
        computed: {
            isClass() {
                return /^ygg_/.test(this.color);
            },
            className() {
                return this.isClass ? this.color : null;
            },
            style() {
                return this.isClass ? null : {
                    'background': this.color
                }
            }
        },
    }
</script>
