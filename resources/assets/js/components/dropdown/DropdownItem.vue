<template>
    <li class="YggDropdown__item">
        <a class="YggDropdown__link" :class="customClass" href="" @mousedown.prevent="" @click.prevent="handleClick">
            <slot></slot>
        </a>
    </li>
</template>

<script>
    export default {
        name: 'YggDropdownItem',
        inject: ['$dropdown'],

        props: {
            customClass: [String, Object]
        },

        methods: {
            handleClick(e) {
                this.$emit('click',e);
                this.$dropdown.$el.blur();
            }
        }
    }
</script>