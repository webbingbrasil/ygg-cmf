<template>
    <div class="YggGlobalFilters">
        <template v-for="filter in filters">
            <YggFilterSelect
                :key="filter.key"
                :label="filter.label"
                :multiple="filter.multiple"
                :required="filter.required"
                :search-keys="filter.searchKeys"
                :searchable="filter.searchable"
                :template="filter.template"
                :value="filterValue(filter.key)"
                :values="filter.values"
                @close="handleClosed(filter)"
                @input="handleFilterChanged(filter, $event)"
                @open="handleOpened(filter)"
            />
        </template>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import YggFilterSelect from '../list/filters/FilterSelect.vue';
    import {BASE_URL} from "../../consts";
    import debounce from 'lodash/debounce';
    import Vue from 'vue';

    export default {
        inject: {
            mainLoading: {
                default: new Vue()
            },
        },
        components: {
            YggFilterSelect
        },
        computed: {
            ...mapGetters('global-filters', {
                filters: 'filters/filters',
                filterValue: 'filters/value',
            }),
        },
        methods: {
            handleFilterChanged(filter, value) {
                this.$store.dispatch('global-filters/post', {filter, value})
                    .then(() => {
                        this.mainLoading.$emit('show');
                        location.href = BASE_URL;
                    });
            },
            handleOpened() {
                this.$emit('open');
            },
            handleClosed() {
                this.$emit('close');
            },
            updateLayout: debounce(function () {
                [...this.$el.querySelectorAll('.YggFilterSelect')].forEach(select => {
                    const container = this.$el.parentElement;
                    const height = (container.offsetHeight - select.offsetHeight) - (select.offsetTop - container.offsetTop);
                    const dropdown = select.querySelector('.YggAutocomplete .multiselect__content');
                    dropdown.style.height = `${height}px`;
                });
            }, 300)
        },
        mounted() {
            this.updateLayout();
            window.addEventListener('resize', this.updateLayout);
        },
        destroyed() {
            window.removeEventListener('resize', this.updateLayout);
        }
    }
</script>
