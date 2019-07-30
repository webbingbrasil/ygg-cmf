<template>
    <YggActionBar>
        <template slot="extras">
            <div class="row mx-n2">
                <template v-for="filter in filters">
                    <div class="col-auto px-2">
                        <YggFilter
                            :filter="filter"
                            :key="filter.id"
                            :value="filterValue(filter.key)"
                            @input="handleFilterChanged(filter, $event)"
                        />
                    </div>
                </template>
            </div>
        </template>
        <template v-if="actions.length" slot="extras-right">
            <YggActionsDropdown class="YggActionBar__actions-dropdown YggActionBar__actions-dropdown--actions"
                                :actions="actions"
                                @select="handleActionSelected"
            >
                <div slot="text">
                    {{ l('dashboard.actions.dashboard.label') }}
                </div>
            </YggActionsDropdown>
        </template>
    </YggActionBar>
</template>

<script>
    import YggActionBar from './ActionBar.vue';
    import YggFilter from '../list/Filter';
    import YggActionsDropdown from '../actions/ActionsDropdown.vue';
    import {Localization} from "../../mixins";
    import {mapGetters} from 'vuex';

    export default {
        name: 'YggActionBarDashboard',
        mixins: [Localization],
        components: {
            YggActionBar,
            YggFilter,
            YggActionsDropdown,
        },
        props: {
            actions: Array,
        },
        computed: {
            ...mapGetters('dashboard', {
                filters: 'filters/filters',
                filterValue: 'filters/value',
                filterNextQuery: 'filters/nextQuery',
            })
        },
        methods: {
            filterKey(filter) {
                return `actionbardashboard_${filter.key}`;
            },
            handleFilterChanged(filter, value) {
                this.$router.push({
                    query: {
                        ...this.$route.query,
                        ...this.filterNextQuery({ filter, value }),
                    }
                });
            },
            handleActionSelected(action) {
                this.$emit('action', action);
            }
        }
    }
</script>
