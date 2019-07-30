<template>
    <ygg-action-bar :ready="ready" container>
        <template slot="left">
            <button @click="emitAction('cancel')" class="YggButton YggButton--secondary-accent">
                {{ showBackButton ? label('back_button') : label('cancel_button') }}
            </button>

            <div class="w-100 h-100" v-if="showDeleteButton">
                <collapse transition-class="YggButton__collapse-transition">
                    <template slot="frame-0" slot-scope="frame">
                        <button @click="frame.next(focusDelete)" class="YggButton YggButton--danger">
                            <svg fill-rule='evenodd' height='16' viewBox='0 0 16 24' width='16'>
                                <path d='M4 0h8v2H4zM0 3v4h1v17h14V7h1V3H0zm13 18H3V8h10v13z'></path>
                                <path d='M5 10h2v9H5zm4 0h2v9H9z'></path>
                            </svg>
                        </button>
                    </template>
                    <template slot="frame-1" slot-scope="frame">
                        <button @blur="frame.next()" @click="emitAction('delete')" class="YggButton YggButton--danger"
                                ref="openDelete">
                            {{ label('delete_button') }}
                        </button>
                    </template>
                </collapse>
            </div>
        </template>
        <template slot="right">
            <button @click="emitAction('submit')" class="YggButton YggButton--accent" v-if="showSubmitButton">
                {{ label('submit_button',opType) }}
            </button>
        </template>
    </ygg-action-bar>
</template>

<script>
    import YggActionBar from './ActionBar';
    import ActionBarMixin from './ActionBarMixin';
    import YggLocaleSelector from '../LocaleSelector';
    import YggDropdown from '../dropdown/Dropdown.vue';
    import YggDropdownItem from '../dropdown/DropdownItem.vue';
    import Collapse from '../Collapse';
    import {ActionEvents} from '../../mixins';
    import {lang} from '../../mixins/Localization';

    export default {
        name: 'YggActionBarForm',
        mixins: [ActionBarMixin, ActionEvents],
        components: {
            YggActionBar,
            YggLocaleSelector,
            YggDropdown,
            YggDropdownItem,
            Collapse
        },
        data() {
            return {
                showSubmitButton: false,
                showDeleteButton: false,
                showBackButton: false,
                deleteButtonOpened: false,
                opType: 'create', // or 'update'
                actionsState: null
            }
        },
        methods: {
            focusDelete() {
                if (this.$refs.openDelete) {
                    this.$refs.openDelete.focus();
                }
            },
            label(element, extra) {
                let localeKey = `action_bar.form.${element}`, stateLabel;
                if (this.actionsState) {
                    let {state, modifier} = this.actionsState;
                    stateLabel = lang(`${localeKey}.${state}.${modifier}`);
                }
                if (!stateLabel && extra) localeKey += `.${extra}`;
                return stateLabel || lang(localeKey);
            }
        },
        actions: {
            setup(config) {
                let {showSubmitButton, showDeleteButton, showBackButton, opType} = config;
                this.showSubmitButton = showSubmitButton;
                this.showDeleteButton = showDeleteButton;
                this.showBackButton = showBackButton;
                this.opType = opType;
            },
            updateActionsState(actionsState) {
                this.actionsState = actionsState;
            }
        }
    }
</script>
