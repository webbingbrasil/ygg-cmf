<template>
    <ygg-multiselect
        :allow-empty="false"
        :class="errorClasses"
        :options="locales"
        :searchable="false"
        :value="value"
        @input="$emit('input',$event)"
        class="YggLocaleSelector d-inline-block"
    >
        <div :class="optionClasses(props.option)" slot="option" slot-scope="props">{{ props.option }}</div>
    </ygg-multiselect>
</template>

<script>
    import YggMultiselect from './Multiselect';

    export default {
        name: 'YggLocaleSelector',
        components: {
            YggMultiselect
        },
        props: {
            locales: Array,
            value: String,
            errors: Object
        },
        computed: {
            hasLocaleErrors() {
                return this.errors && !!Object.keys(this.errors).length;
            },
            errorClasses() {
                return this.hasError(this.value) ? 'YggLocaleSelector--has-error' : this.hasLocaleErrors ? 'YggLocaleSelector--has-partial-error' : '';
            }
        },
        methods: {
            optionClasses(locale) {
                return this.hasError(locale) ? 'error-dot' : '';
            },
            hasError(locale) {
                return this.errors && this.errors[locale];
            }
        }
    }
</script>
