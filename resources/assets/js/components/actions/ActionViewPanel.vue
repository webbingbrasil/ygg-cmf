<template>
    <div>
        <div class="YggViewPanel__glasspane" v-show="visible" @click="handleBackdropClicked"></div>
        <transition
            enter-class="YggViewPanel--collapsed"
            enter-active-class="YggViewPanel--expanding"
            enter-to-class="YggViewPanel--expanded"
            leave-class="YggViewPanel--expanded"
            leave-active-class="YggViewPanel--collapsing"
            leave-to-class="YggViewPanel--collapsed"
        >
            <template v-if="visible">
                <div class="YggViewPanel">
                    <iframe src="about:blank" v-srcdoc="content" sandbox="allow-forms allow-scripts allow-same-origin allow-popups"></iframe>
                </div>
            </template>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'YggViewPanel',
        props: {
            content: String
        },
        computed:{
            visible() {
                return !!this.content;
            },
        },
        methods: {
            handleBackdropClicked() {
                this.$emit('close');
            },
        },
        directives: {
            srcdoc: {
                inserted(el, { value }) {
                    el.contentWindow.document.write(value);
                }
            }
        },
    }
</script>