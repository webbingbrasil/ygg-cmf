import { parseBlobJSONContent, getFileName } from "../../util";

export default {
    data() {
        return {
            actionCurrentForm: null,
            actionViewContent: null,
        }
    },
    methods: {
        transformActionForm(form) {
            return {
                ...form,
                layout: { tabs: [{ columns: [{fields:form.layout}] }] }
            };
        },
        downloadActionFile(response) {
            let $link = document.createElement('a');
            this.$el.appendChild($link);
            $link.href = URL.createObjectURL(response.data);
            $link.download = getFileName(response.headers);
            $link.click();
        },
        async handleActionResponse(response) {
            if(response.data.type === 'application/json') {
                const data = await parseBlobJSONContent(response.data);
                const handler = this.actionHandlers[data.action];

                if(handler) {
                    handler(data);
                }
            } else {
                this.downloadActionFile(response);
            }
        },
        async postActionForm({ postFn }) {
            const response = await this.$refs.actionForm.submit({ postFn });
            await this.handleActionResponse(response);
            this.actionCurrentForm = null;
        },
        async showActionForm(action, { postForm, getFormData }) {
            const data = action.fetch_initial_data ? await getFormData() : {};
            const post = () => this.postActionForm({ postFn:postForm });

            this.actionCurrentForm = this.transformActionForm({ ...action.form, data });

            this.$refs.actionForm.$on('submit', post);
            this.$refs.actionForm.$once('close', () => {
                this.$refs.actionForm.$off('submit', post);
                this.actionCurrentForm = null;
            });
        },
        async sendAction(action, { postAction, getFormData, postForm }) {
            const { form, confirmation } = action;
            if(form) {
                return this.showActionForm(action, { postForm, getFormData });
            }
            if(confirmation) {
                await new Promise(resolve => {
                    this.actionsBus.$emit('showMainModal', {
                        title: this.l('modals.action.confirm.title'),
                        text: confirmation,
                        okCallback: resolve,
                    });
                });
            }
            try {
                let response = await postAction();
                await this.handleActionResponse(response);
            } catch(e) {
                console.error(e);
            }
        },

        /** mixin API */
        addActionHandlers(handlers) {
            this.actionHandlers = {
                ...this.actionHandlers,
                ...handlers,
            };
        },

        /** Action actions handlers */
        handleReloadAction() {
            this.init();
        },
        handleInfoAction(data) {
            this.actionsBus.$emit('showMainModal', {
                title: this.l('modals.action.info.title'),
                text: data.message,
                okCloseOnly: true
            });
        },
        handleViewAction(data) {
            this.actionViewContent = data.html;
        },
        handleLinkAction(data) {
            window.location.href = data.link;
        },

        /** Events */
        handleActionViewPanelClosed() {
            this.actionViewContent = null;
        },
    },
    created() {
        // default handlers
        this.addActionHandlers({
            'reload': this.handleReloadAction,
            'info': this.handleInfoAction,
            'link': this.handleLinkAction,
            'view': this.handleViewAction,
        });
    },
    mounted() {
        if(!this.$refs.actionForm) {
            console.error('withActions: ActionForm not found');
        }
    }
}
