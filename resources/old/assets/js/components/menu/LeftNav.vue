<template>
    <nav :class="classes"
         @click="handleClicked"
         aria-label="Menu Ygg"
         class="YggLeftNav"
         role="navigation"
    >
        <div class="YggLeftNav__top-icon">
            <i :class="currentIcon" class="fa"></i>
        </div>
        <div class="flex-grow-0">
            <div class="YggLeftNav__title-container position-relative">
                <h2 class="YggLeftNav__title">{{ title }}</h2>
            </div>
        </div>
        <div class="flex-grow-1" style="min-height: 0">
            <template v-if="ready">
                <div class="YggLeftNav__content d-flex flex-column h-100">
                    <div class="YggLeftNav__inner flex-grow-1" style="min-height: 0">
                        <GlobalFilters @close="handleGlobalFilterClosed" @open="handleGlobalFilterOpened"/>
                        <slot/>
                    </div>
                    <div class="flex-grow-0">
                        <div @click.stop="collapsed = !collapsed" class="YggLeftNav__collapse">
                            <a @click.prevent class="YggLeftNav__collapse-link" href="#">
                                <svg class="YggLeftNav__collapse-arrow" fill-rule="evenodd" height="12"
                                     viewBox="0 0 8 12" width="8">
                                    <path d="M7.5 10.6L2.8 6l4.7-4.6L6.1 0 0 6l6.1 6z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="d-flex align-items-center justify-content-center h-100">
                    <YggLoading inline small visible/>
                </div>
            </template>
        </div>
    </nav>
</template>

<script>
    import {Responsive} from '../../mixins';
    import GlobalFilters from './GlobalFilters.vue';
    import {YggLoading} from "../ui";

    export default {
        name: 'YggLeftNav',
        mixins: [Responsive('lg')],
        components: {
            GlobalFilters,
            YggLoading,
        },
        props: {
            items: Array,
            current: String,
            title: String,
            collapseable: {
                type: Boolean,
                default: true,
            },
            hasGlobalFilters: Boolean,
        },
        data() {
            return {
                ready: false,
                collapsed: false,
                state: 'expanded',
                filterOpened: false,
            }
        },
        watch: {
            collapsed: {
                handler(val) {
                    this.$root.$emit('setClass', 'leftNav--collapsed', this.collapsed);
                    // apply transition
                    this.state = val ? 'collapsing' : 'expanding';
                    setTimeout(this.updateState, 250);
                }
            }
        },
        computed: {
            flattenedItems() {
                return this.items.reduce((res, item) =>
                        item.type === 'category'
                            ? [...res, ...item.resources]
                            : [...res, item]
                    , []);
            },
            currentIcon() {
                return this.current === 'dashboard'
                    ? 'fa-dashboard'
                    : (this.flattenedItems.find(e => e.key === this.current) || {}).icon;
            },
            classes() {
                return [
                    `YggLeftNav--${this.state}`,
                    {
                        'YggLeftNav--filter-opened': this.filterOpened,
                        'YggLeftNav--collapseable': this.collapseable,
                    }
                ]
            }
        },
        methods: {
            updateState() {
                this.state = this.collapsed ? 'collapsed' : 'expanded';
            },
            handleGlobalFilterOpened() {
                this.filterOpened = true;
            },
            handleGlobalFilterClosed() {
                this.filterOpened = false;
            },
            handleClicked() {
                if (this.collapsed) {
                    this.collapsed = false;
                }
            },
            async init() {
                if (this.hasGlobalFilters) {
                    await this.$store.dispatch('global-filters/get');
                }
                this.ready = true;
            },
        },
        created() {
            this.collapsed = this.isViewportSmall;
            this.updateState();
            this.init();
        },
    }
</script>
