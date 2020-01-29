<template>
    <div :class="classes" class="YggList">
        <template v-if="showSortButton">
            <button :class="{'YggButton--active':dragActive}"
                    :data-active-text="l('form.list.sort_button.active')"
                    :data-inactive-text="l('form.list.sort_button.inactive')"
                    @click="toggleDrag"
                    class="YggButton YggButton--ghost YggList__sort-button"
                    type="button">
                <svg class="YggButton__icon" fill-rule='evenodd' height='22' viewBox='0 0 24 22' width='24'>
                    <path d='M20 14V0h-4v14h-4l6 8 6-8zM4 8v14h4V8h4L6 0 0 8z'></path>
                </svg>
            </button>
        </template>
        <draggable :list="list" :options="dragOptions" ref="draggable">
            <transition-group name="expand" tag="div">
                <div :class="{'YggList__item--collapsed':dragActive}" :key="listItemData[indexSymbol]"
                     class="YggList__item"
                     v-for="(listItemData, i) in list"
                >
                    <div class="YggModule__inner">
                        <div class="YggModule__content">

                            <template v-if="dragActive && collapsedItemTemplate">
                                <ygg-template :template="collapsedItemTemplate"
                                              :template-data="collapsedItemData(listItemData)"
                                              name="CollapsedItem"></ygg-template>
                            </template>

                            <template v-else>
                                <ygg-list-item :error-identifier="i" :layout="fieldLayout.item">
                                    <template slot-scope="itemFieldLayout">
                                        <ygg-field-display
                                            :config-identifier="itemFieldLayout.key"
                                            :context-data="listItemData"
                                            :context-fields="transformedFields(i)"
                                            :error-identifier="itemFieldLayout.key"
                                            :field-key="itemFieldLayout.key"
                                            :locale="listItemData._fieldsLocale[itemFieldLayout.key]"
                                            :update-data="update(i)"
                                            @locale-change="(key, value)=>updateLocale(i, key, value)"
                                        />
                                    </template>
                                </ygg-list-item>
                                <button @click="remove(i)" class="YggButton YggButton--danger YggButton--sm mt-3"
                                        v-if="!disabled && removable">{{ l('form.list.remove_button') }}
                                </button>
                            </template>

                            <!-- Full size div use to handle the item when drag n drop (c.f draggable options) -->
                            <div class="YggList__overlay-handle" v-if="dragActive"></div>
                        </div>
                    </div>
                    <div class="YggList__new-item-zone" v-if="!disabled && showInsertButton && i<list.length-1">
                        <button @click="insertNewItem(i, $event)" class="YggButton YggButton--sm">{{
                            l('form.list.insert_button') }}
                        </button>
                    </div>
                </div>
            </transition-group><!-- Important comment, do not remove
         -->
            <template slot="footer">
                <button :class="'YggButton--ghost' " :key="-1" @click="add"
                        class="YggButton YggList__add-button"
                        type="button"
                        v-if="!disabled && showAddButton">{{addText}}
                </button>
            </template>
        </draggable>
        <em class="YggList__empty-alert" v-if="readOnly && !list.length">{{l('form.list.empty')}}</em>
    </div>
</template>
<script>
    import Draggable from 'vuedraggable';
    import YggListItem from './ListItem';
    import YggTemplate from '../../../Template';
    import {Localization, ReadOnlyFields} from '../../../../mixins';
    import localize from '../../../../mixins/localize/form';
    import {getDependantFieldsResetData, transformFields} from "../../../../util/form";

    export default {
        name: 'YggList',
        inject: ['$form'],
        mixins: [Localization, ReadOnlyFields('itemFields'), localize('itemFields')],
        components: {
            Draggable,
            YggListItem,
            YggTemplate
        },
        props: {
            fieldKey: String,
            fieldLayout: Object,
            value: Array,
            addable: {
                type: Boolean,
                default: true
            },
            sortable: {
                type: Boolean,
                default: false
            },
            removable: {
                type: Boolean,
                default: false
            },
            addText: {
                type: String,
                default: 'Ajouter un élément'
            },
            itemFields: {
                type: Object,
                required: true,
            },
            collapsedItemTemplate: String,
            maxItemCount: Number,
            itemIdAttribute: String,
            readOnly: Boolean,
            locale: String
        },
        data() {
            return {
                list: [],
                itemFieldsLocale: [],
                dragActive: false,
                lastIndex: 0
            }
        },
        computed: {
            classes() {
                return {
                    'YggList--can-sort': this.showSortButton,
                }
            },
            disabled() {
                return this.readOnly || this.dragActive;
            },
            dragOptions() {
                return {disabled: !this.dragActive, handle: '.YggList__overlay-handle'};
            },
            showAddButton() {
                return this.addable && (this.list.length < this.maxItemCount || !this.maxItemCount);
            },
            showInsertButton() {
                return this.showAddButton && this.sortable;
            },
            showSortButton() {
                return this.sortable && this.list.length > 1;
            },
            itemFieldsKeys() {
                return Object.keys(this.itemFields)
            },
            dragIndexSymbol() {
                return Symbol('dragIndex');
            },
            indexSymbol() {
                return Symbol('index');
            },
        },
        methods: {
            itemData(item) {
                const {id, _fieldsLocale, ...data} = item;
                return data;
            },
            transformedFields(i) {
                const item = this.list[i];
                const data = this.itemData(item);
                const fields = this.readOnly || this.dragActive
                    ? this.readOnlyFields
                    : this.itemFields;
                return transformFields(fields, data);
            },
            indexedList() {
                return (this.value || []).map((v, i) => this.withLocale({
                    [this.indexSymbol]: i, ...v
                }));
            },
            createItem() {
                return this.itemFieldsKeys.reduce((res, fieldKey) => {
                    res[fieldKey] = null;
                    return res;
                }, this.withLocale({
                    [this.itemIdAttribute]: null,
                    [this.indexSymbol]: this.lastIndex++
                }));
            },
            insertNewItem(i, $event) {
                $event.target && $event.target.blur();
                this.list.splice(i + 1, 0, this.createItem());
            },
            add() {
                this.list.push(this.createItem());
            },
            remove(i) {
                this.list.splice(i, 1);
            },
            update(i) {
                return (key, value, {forced} = {}) => {
                    const item = {...this.list[i]};
                    const data = {
                        ...(!forced ? getDependantFieldsResetData(this.itemFields, key, () =>
                            this.fieldLocalizedValue(key, null, item, item._fieldsLocale)
                        ) : null),
                        [key]: this.fieldLocalizedValue(key, value, item, item._fieldsLocale),
                    };
                    Object.assign(this.list[i], data);
                }
            },
            updateLocale(i, key, value) {
                this.$set(this.list[i]._fieldsLocale, key, value);
            },
            collapsedItemData(itemData) {
                return {$index: itemData[this.dragIndexSymbol], ...itemData};
            },
            toggleDrag() {
                this.dragActive = !this.dragActive;
                this.list.forEach((item, i) => item[this.dragIndexSymbol] = i);
            },
            withLocale(item) {
                return {
                    ...item, _fieldsLocale: this.defaultFieldLocaleMap({
                        fields: this.itemFields,
                        locales: this.$form.locales
                    })
                };
            },
            initList() {
                this.list = this.indexedList();
                this.lastIndex = this.list.length;
                // make value === list, to update changes
                this.$emit('input', this.list);
            },
        },
        created() {
            this.localized = this.$form.localized;
            this.initList();
        },
    }
</script>
