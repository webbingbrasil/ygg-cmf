<template>
    <ygg-action-bar class="YggActionBarList" :class="{'YggActionBarList--search-active':searchActive}">
        <template slot="left">
            <span class="text-content">{{ count }} {{ l('action_bar.list.items_count') }}</span>
        </template>
        <template slot="right">
            <div v-if="canSearch && !reorderActive" class="YggActionBar__search YggSearch YggSearch--lg" :class="{'YggSearch--active':searchActive}" role="search">
                <form @submit.prevent="handleSearchSubmitted">
                    <label id="ab-search-label" class="YggSearch__label" for="ab-search-input">{{ l('action_bar.list.search.placeholder') }}</label>
                    <input class="YggSearch__input"
                           :placeholder="l('action_bar.list.search.placeholder')"
                           :value="search"
                           @blur="handleSearchBlur"
                           @focus="handleSearchFocused"
                           @input="handleSearchInput"
                           aria-labelledby="ab-search-label"
                           id="ab-search-input"
                           ref="search"
                           role="search"
                           type="text"
                    >
                    <svg class="YggSearch__magnifier" width="16" height="16" viewBox="0 0 16 16" fill-rule="evenodd">
                        <path d="M6 2c2.2 0 4 1.8 4 4s-1.8 4-4 4-4-1.8-4-4 1.8-4 4-4zm0-2C2.7 0 0 2.7 0 6s2.7 6 6 6 6-2.7 6-6-2.7-6-6-6zM16 13.8L13.8 16l-3.6-3.6 2.2-2.2z"></path>
                        <path d="M16 13.8L13.8 16l-3.6-3.6 2.2-2.2z"></path>
                    </svg>
                    <svg class="YggSearch__close" :class="{'YggSearch__close--hidden':!(search||'').length}"
                         @click="handleClearButtonClicked"
                         fill-rule="evenodd" height="16" viewBox="0 0 16 16" width="16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm3.5 10.1l-1.4 1.4L8 9.4l-2.1 2.1-1.4-1.4L6.6 8 4.5 5.9l1.4-1.4L8 6.6l2.1-2.1 1.4 1.4L9.4 8l2.1 2.1z"></path>
                    </svg>
                </form>
            </div>
            <template v-if="canReorder">
                <template v-if="reorderActive">
                    <button class="YggButton YggButton--secondary-accent" @click="handleReorderButtonClicked">
                        {{ l('action_bar.list.reorder_button.cancel') }}
                    </button>
                    <button class="YggButton YggButton--accent" @click="handleReorderSubmitButtonClicked">
                        {{ l('action_bar.list.reorder_button.finish') }}
                    </button>
                </template>
                <template v-else>
                    <button class="YggButton YggButton--secondary-accent" @click="handleReorderButtonClicked">
                        {{ l('action_bar.list.reorder_button') }}
                    </button>
                </template>
            </template>

            <template v-if="!reorderActive">
                <template v-if="canCreate">
                    <ygg-dropdown v-if="hasForms" class="YggActionBar__forms-dropdown" :text="l('action_bar.list.forms_dropdown')">
                        <ygg-dropdown-item v-for="(form,key) in forms" @click="handleCreateFormSelected(form)" :key="key" >
                            <ygg-item-visual :item="form" icon-class="fa-fw"/>{{ form.label }}
                        </ygg-dropdown-item>
                    </ygg-dropdown>
                    <button v-else class="YggButton YggButton--accent" @click="handleCreateButtonClicked">
                        {{ l('action_bar.list.create_button') }}
                    </button>
                </template>
            </template>
        </template>
        <template slot="extras">
            <template v-if="!reorderActive"></template>
            <div class="row mx-n2">
                <template v-for="filter in filters">
                    <div class="col-auto px-2">
                        <YggFilter
                            :filter="filter"
                            :key="filter.id"
                            :value="filtersValues[filter.key]"
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
                    {{ l('resource_list.actions.resource.label') }}
                </div>
            </YggActionsDropdown>
        </template>
    </ygg-action-bar>
</template>

<script>
    import YggActionBar from './ActionBar';
    import {Localization} from '../../mixins';
    import YggText from '../form/fields/Text';
    import YggFilter from '../list/Filter';
    import YggDropdown from '../dropdown/Dropdown';
    import YggDropdownItem from '../dropdown/DropdownItem';
    import YggItemVisual from '../ui/ItemVisual';
    import YggActionsDropdown from '../actions/ActionsDropdown';

    export default {
        name: 'YggActionBarList',
        components : {
            YggActionBar,
            YggText,
            YggDropdown,
            YggDropdownItem,
            YggItemVisual,
            YggActionsDropdown,
            YggFilter,
        },
        mixins: [Localization],
        props: {
            count: Number,
            search: String,
            filters: Array,
            filtersValues: Object,
            actions: Array,
            forms: Array,
            canCreate: Boolean,
            canReorder: Boolean,
            canSearch: Boolean,
            reorderActive: Boolean
        },
        data() {
            return {
                searchActive: false
            }
        },
        computed: {
            hasForms() {
                return this.forms && this.forms.length > 0;
            }
        },
        methods: {
            handleSearchFocused() {
                this.searchActive = true;
            },
            handleSearchBlur() {
                this.searchActive = false;
            },
            handleSearchInput(e) {
                this.$emit('search-change', e.target.value);
            },
            handleClearButtonClicked() {
                this.$emit('search-change', '');
                this.$refs.search.focus();
            },
            handleSearchSubmitted() {
                this.$emit('search-submit');
            },
            handleFilterChanged(filter, value) {
                this.$emit('filter-change', filter, value);
            },
            handleReorderButtonClicked() {
                this.$emit('reorder-click');
                document.activeElement.blur();
            },
            handleReorderSubmitButtonClicked() {
                this.$emit('reorder-submit');
            },
            handleActionSelected(action) {
                this.$emit('action', action);
            },
            handleCreateButtonClicked() {
                this.$emit('create');
            },
            handleCreateFormSelected(form) {
                this.$emit('create', form);
            }
        }
    }
</script>
