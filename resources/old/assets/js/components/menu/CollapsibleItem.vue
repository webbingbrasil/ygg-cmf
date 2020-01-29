<template>
    <li :class="{'YggLeftNav__item--expanded': expanded}" @keydown.enter="toggle"
        class="YggLeftNav__item YggLeftNav__item--has-children" tabindex="0" v-show="ready">
        <a @click="toggle" class="YggLeftNav__item-link">
            <slot name="label">
                {{ label }}
            </slot>
            <div class="YggLeftNav__item-icon">
                <svg class="YggLeftNav__icon" fill-rule="evenodd" height="5" viewBox="0 0 10 5" width="10">
                    <path d="M10 0L5 5 0 0z"></path>
                </svg>
            </div>
        </a>
        <ul aria-hidden="true" class="YggLeftNav__list YggLeftNav__list--nested" role="menu">
            <slot></slot>
        </ul>
    </li>
</template>

<script>
    import NavItem from './NavItem';

    export default {
        name: 'YggCollapsibleItem',
        props: {
            label: String
        },
        data() {
            return {
                expanded: false,
                ready: false
            }
        },
        computed: {
            navItems() {
                return this.$slots.default
                    .map(slot => slot.componentInstance)
                    .filter(comp => comp && comp.$options.name === NavItem.name);
            }
        },
        methods: {
            toggle() {
                this.expanded = !this.expanded;
            }
        },
        mounted() {
            this.expanded = this.navItems.some(i => i.current);
            this.$nextTick(_ => this.ready = true);
        }
    }
</script>
