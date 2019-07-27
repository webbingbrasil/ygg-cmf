<template>
    <YggActionBar>
        <template slot="extras">
            <YggFilterSelect
                v-for="filter in filters"
                :name="filter.label"
                :values="filter.values"
                :value="filterValue(filter.key)"
                :filter-key="filterKey(filter)"
                :multiple="filter.multiple"
                :required="filter.required"
                :template="filter.template"
                :search-keys="filter.searchKeys"
                :searchable="filter.searchable"
                :key="filter.key"
                @input="handleFilterChanged(filter, $event)"
            />
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
    import YggFilterSelect from '../list/FilterSelect.vue';
    import YggActionsDropdown from '../actions/ActionsDropdown.vue';
    import { Localization } from "../../mixins";
    import { mapGetters } from 'vuex';

    export default {
        name: 'YggActionBarDashboard',
        mixins: [Localization],
        components: {
            YggActionBar,
            YggFilterSelect,
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