<template>
    <div class="YggActionView">
        <template v-if="showErrorPage">
            <div class="container">
                <h1>Error {{errorPageData.status}}</h1>
                <p>{{errorPageData.message}}</p>
            </div>
        </template>
        <template v-else>
            <component :is="barComp" v-if="barComp"/>
            <slot/>
            <notifications animation-name="slideRight" position="top right" reverse style="top:6rem">
                <template slot="body" slot-scope="{ item, close }">
                    <div :class="`YggToastNotification--${item.type||'info'}`" class="YggToastNotification"
                         data-test="notification" role="alert">
                        <div class="YggToastNotification__details">
                            <h3 class="YggToastNotification__title mb-2">{{ item.title }}</h3>
                            <p class="YggToastNotification__caption" v-html="item.text" v-if="!!item.text"></p>
                        </div>
                        <button @click="close" class="YggToastNotification__close-button" data-test="close-notification"
                                type="button">
                            <svg aria-label="close" class="YggToastNotification__icon" fill-rule="evenodd" height="10"
                                 viewBox="0 0 10 10" width="10">
                                <path
                                    d="M9.8 8.6L8.4 10 5 6.4 1.4 10 0 8.6 3.6 5 .1 1.4 1.5 0 5 3.6 8.6 0 10 1.4 6.4 5z"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </notifications>
            <ygg-modal :key="id" @hidden="modal.hiddenCallback"
                       @ok="modal.okCallback" v-bind="modal.props" v-for="(modal,id) in mainModalsData">
                {{modal.text}}
            </ygg-modal>
        </template>
    </div>
</template>

<script>
    import {actionBarByContext} from './action-bar';
    import EventBus from './EventBus';
    import {api} from "../api";
    import YggModal from './Modal';

    const noop = () => {
    };
    export default {
        name: 'YggActionView',
        components: {
            YggModal
        },
        provide() {
            return {
                actionsBus: new EventBus({name: 'YggActionsEventBus'}),
                axiosInstance: api
            }
        },
        props: {
            context: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                mainModalsData: {},
                mainModalId: 0,
                showErrorPage: false,
                errorPageData: null
            }
        },
        computed: {
            barComp() {
                return actionBarByContext(this.context);
            },
        },
        methods: {
            showMainModal({text, okCallback = noop, okCloseOnly, isError, ...sharedProps}) {
                const curId = this.mainModalId;
                const hiddenCallback = () => this.$delete(this.mainModalsData, curId);
                this.$set(this.mainModalsData, curId, {
                    props: {
                        ...sharedProps,
                        okOnly: okCloseOnly,
                        noCloseOnBackdrop: okCloseOnly,
                        noCloseOnEsc: okCloseOnly,
                        visible: true,
                        isError
                    },
                    okCallback, hiddenCallback,
                    text,
                });
                this.mainModalId++;
            }
        },
        created() {
            let {actionsBus, axiosInstance} = this._provided;
            actionsBus.$on('showMainModal', this.showMainModal);
            axiosInstance.interceptors.response.use(c => c, error => {
                let {response: {status, data}, config: {method}} = error;
                if (method === 'get' && status === 404) {
                    this.showErrorPage = true;
                    this.errorPageData = {
                        status, message: data.message
                    }
                }
                return Promise.reject(error);
            });
        }
    }
</script>
