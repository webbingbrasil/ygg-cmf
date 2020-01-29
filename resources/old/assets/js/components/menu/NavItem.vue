<template>
    <li :class="{'YggLeftNav__item--active':current, 'YggLeftNav__item--disabled':disabled}"
        class="YggLeftNav__item"
        role="menuitem"
    >
        <template v-if="disabled">
             <span class="YggLeftNav__item-link">
                 <slot></slot>
             </span>
        </template>
        <template v-else>
            <a :href="link" class="YggLeftNav__item-link">
                <slot></slot>
            </a>
        </template>
    </li>
</template>

<script>
    export default {
        name: 'YggNavItem',
        props: {
            current: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            },
            link: String
        },
        data() {
            return {}
        },
    }
</script>
