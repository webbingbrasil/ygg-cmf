<template>
    <div class="YggWidgetPanel">
        <ygg-template name="WidgetPanel" :template="template" :template-data="value.data"></ygg-template>
    </div>
</template>

<script>
    import YggTemplate from '../../Template';

    export default {
        name:'YggWidgetPanel',
        components: {
            YggTemplate
        },
        props: {
            value: Object,
            template: String,
        }
    }
</script>