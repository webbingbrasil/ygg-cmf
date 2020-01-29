<template>
    <div>
        <div class="editor-toolbar">
            <template v-for="part in toolbar">
                <i class="separator" v-if="part==='|'">|</i>
                <button :class="buttons[part].icon"
                        class="fa"
                        tabindex="-1"
                        v-button-data="buttons[part]"
                        v-else-if="buttons[part]"
                >
                </button>
            </template>
        </div>
        <div class="trix-dialogs" data-trix-dialogs>
            <div class="trix-dialog trix-dialog--link" data-trix-dialog="href" data-trix-dialog-attribute="href">
                <div class="trix-dialog__link-fields">
                    <input :placeholder="lSub('dialogs.add_link.input_placeholder')"
                           class="trix-input trix-input--dialog" data-trix-input name="href" required type="url">
                    <div class="trix-button-group">
                        <input :value="lSub('dialogs.add_link.link_button')" class="trix-button trix-button--dialog"
                               data-trix-method="setAttribute" type="button">
                        <input :value="lSub('dialogs.add_link.unlink_button')" class="trix-button trix-button--dialog"
                               data-trix-method="removeAttribute" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {buttons} from "./config";
    import {LocalizationBase} from "../../../../mixins";

    export default {
        mixins: [LocalizationBase('form.wysiwyg')],
        props: {
            toolbar: Array,
        },
        data() {
            return {
                buttons
            }
        },
        directives: {
            buttonData(el, {value}) {
                let {attribute, action} = value;
                attribute && el.setAttribute('data-trix-attribute', attribute);
                action && el.setAttribute('data-trix-action', action);
            },
        }
    }
</script>
