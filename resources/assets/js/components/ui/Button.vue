<template>
    <button :class="buttonClasses" class="YggButton" v-on="$listeners">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: String,
            outline: Boolean,
            danger: Boolean,
            small: Boolean
        },
        computed: {
            buttonClasses() {
                return [
                    this.type ? `YggButton--${this.type}` : '', {
                        'YggButton--secondary': this.outline,
                        'YggButton--danger': this.danger,
                        'YggButton--sm': this.small
                    }
                ]
            }
        }
    }
</script>
