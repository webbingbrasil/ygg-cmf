<template>
    <div :class="cardClasses" class="YggModule">
        <div class="YggModule__inner">
            <div class="YggModule__content">
                <slot></slot>
            </div>
            <template v-if="hasClose">
                <button @click="$emit('close-click')" class="YggModule__close-button" type="button">
                    <svg aria-label="close"
                         class="YggModule__close-icon" fill-rule="evenodd" height="10" viewBox="0 0 10 10" width="10">
                        <path
                            d="M9.8 8.6L8.4 10 5 6.4 1.4 10 0 8.6 3.6 5 .1 1.4 1.5 0 5 3.6 8.6 0 10 1.4 6.4 5z"></path>
                    </svg>
                </button>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'YggCard',
        props: {
            light: Boolean,
            hasClose: Boolean
        },
        computed: {
            cardClasses() {
                return {
                    'YggModule--light': this.light
                }
            }
        }
    }
</script>
