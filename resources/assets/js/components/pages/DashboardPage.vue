<template>
    <div class="YggDashboardPage">
        <template v-if="ready">
            <YggActionBarDashboard :actions="actions" @action="handleActionRequested" />
            <div class="container">
                <YggGrid :rows="layout.rows">
                    <template slot-scope="widgetLayout">
                        <YggWidget
                            :value="data[widgetLayout.key]"
                            :widget-props="widgets[widgetLayout.key]"
                            :widget-type="widgets[widgetLayout.key].type"
                        />
                    </template>
                </YggGrid>
            </div>
        </template>

        <YggActionFormModal :form="actionCurrentForm" ref="actionForm" />
        <YggActionViewPanel :content="actionViewContent" @close="handleActionViewPanelClosed" />
    </div>
</template>

<script>
    import YggGrid from '../Grid.vue';
    import YggWidget from '../dashboard/Widget.vue';
    import YggActionBarDashboard from '../action-bar/ActionBarDashboard.vue';
    import YggActionFormModal from '../actions/ActionFormModal.vue';
    import YggActionViewPanel from '../actions/ActionViewPanel.vue';
    import {withAxiosInterceptors} from "../DynamicViewMixin";
    import withActions from '../../mixins/page/with-actions';
    import {mapGetters, mapState} from 'vuex';

    export default {
        name:'YggDashboardPage',
        mixins: [withAxiosInterceptors, withActions],
        components: {
            YggGrid,
            YggWidget,
            YggActionBarDashboard,
            YggActionFormModal,
            YggActionViewPanel,
        },
        data() {
            return {
                ready: false
            }
        },
        watch: {
            '$route': 'init'
        },
        computed: {
            ...mapState('dashboard', {
                data: state => state.data,
                widgets: state => state.widgets,
                layout: state => state.layout
            }),
            ...mapGetters('dashboard', {
                getFiltersValuesFromQuery: 'filters/getValuesFromQuery',
                actionsForType: 'actions/forType',
            }),
            actions() {
                return this.actionsForType('dashboard') || [];
            },
        },
        methods: {
            handleActionRequested(action) {
                const query = this.$route.query;
                this.sendAction(action, {
                    postAction: () => this.$store.dispatch('dashboard/postAction', { action, query }),
                    postForm: data => this.$store.dispatch('dashboard/postAction', { action, query, data }),
                    getFormData: () => this.$store.dispatch('dashboard/getActionFormData', { action, query }),
                });
            },
            async init() {
                await this.$store.dispatch('dashboard/setDashboardKey', this.$route.params.id);
                await this.$store.dispatch('dashboard/get', {
                    filtersValues: this.getFiltersValuesFromQuery(this.$route.query)
                });
                this.ready = true;
            },
        },
        created() {
            this.init();
        }
    }
</script>
