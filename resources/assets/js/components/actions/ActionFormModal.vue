<template>
    <YggModal :visible.sync="visible" @ok="handleSubmitButtonClicked" @hidden="handleClosed">
        <transition>
            <YggForm
                v-if="visible"
                :props="form"
                independant
                ignore-authorizations
                style="transition-duration: 300ms"
                ref="form"
            />
        </transition>
    </YggModal>
</template>

<script>
    import YggModal from '../Modal.vue';
    import YggForm from '../form/Form.vue';

    export default {
        components: {
            YggModal,
            YggForm,
        },
        props: {
            form: Object,
        },
        data() {
            return {
                visible: false
            }
        },
        watch: {
            form(form) {
                this.visible = !!form;
            }
        },
        methods: {
            submit(...args) {
                return this.$refs.form.submit(...args);
            },
            handleSubmitButtonClicked(e) {
                e.preventDefault();
                this.$emit('submit');
            },
            handleClosed() {
                this.$emit('close');
            }
        },
    }
</script>