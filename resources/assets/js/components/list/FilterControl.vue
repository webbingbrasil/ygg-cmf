<template>
    <div :class="classes" @click="handleClicked" class="YggFilterControl">
        <span class="YggFilterControl__text">
            {{ label }}
        </span>
        <slot/>
        <template v-if="!noCaret">
            <span class="YggFilterControl__caret-container">
                <DropdownArrow class="YggFilterControl__caret"/>
            </span>
        </template>
    </div>
</template>

<script>
    import DropdownArrow from '../dropdown/Arrow';

    export default {
        name: 'YggFilterControl',
        props: {
            opened: Boolean,
            label: String,
            noCaret: Boolean,
        },
        components: {
            DropdownArrow,
        },
        computed: {
            classes() {
                return {
                    'YggFilterControl--open': this.opened,
                }
            },
        },
        methods: {
            handleClicked() {
                this.$emit('click');
            },
        },
    }
</script>
