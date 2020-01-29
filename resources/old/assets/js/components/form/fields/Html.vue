<template>
    <ygg-template :template="template" :template-data="value" name="Html"></ygg-template>
</template>

<script>
    import YggTemplate from '../../Template';

    export default {
        name: 'YggHtml',

        components: {
            YggTemplate
        },

        props: {
            value: Object,
            template: String
        }
    }
</script>
