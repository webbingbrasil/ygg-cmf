<template>
    <div v-if="inline" class="YggLoading" :class="{'YggLoading--small':small}" v-show="show">
        <svg class="YggLoading__svg" viewBox="-75 -75 150 150">
            <circle cx="0" cy="0" r="37.5" />
        </svg>
    </div>
    <div v-else class="YggLoading__overlay" v-show="show">
        <div class="YggLoading" :class="{'YggLoading--small':small}">
            <svg class="YggLoading__svg" viewBox="-75 -75 150 150">
                <circle cx="0" cy="0" r="37.5" />
            </svg>
        </div>
    </div>
</template>



<script>
    import Vue from 'vue';
    export default {
        name: 'YggLoading',
        props: {
            visible: Boolean,
            inline: Boolean,
            small: Boolean
        },
        data() {
            return {
                show: this.visible
            }
        },
        watch: {
            visible(v) {
                this.show = v;
            }
        },
        created() {
            this.$on('show', _ => this.show = true);
            this.$on('hide', _ => this.show = false);
        }
    }
</script>