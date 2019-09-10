<template>
    <div class="YggWidgetPanel">
        <YggForm
                :resource-key="resourceKey"
                :instance-id="instanceId"
                style="transition-duration: 300ms"
                reloadOnSubmit
                ref="form"
        />
        <div>
            <button @click="submit()" class="YggButton YggButton--accent">
                {{ label('submit_button',submitLabel) }}
            </button>
        </div>
    </div>
</template>

<script>
    import YggForm from '../../form/Form';
    import {lang} from '../../../mixins/Localization';

    export default {
        name:'YggWidgetForm',
        components: {
            YggForm
        },
        props: {
            resourceKey: String,
            instanceId: String,
            submitLabel: String
        },
        methods: {
            submit(...args) {
                return this.$refs.form.submit(...args);
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
        computed: {}
    }
</script>