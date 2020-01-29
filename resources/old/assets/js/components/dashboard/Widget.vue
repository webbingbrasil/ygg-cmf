<template>
    <article class="YggWidget YggCard" :class="{'YggWidget--chart':widgetType==='graph', 'YggWidget--link':hasLink}">
        <component :is="hasLink ? 'a' : 'div'" :href="widgetProps.link" :class="{YggWidget__link:hasLink}">
            <div class="YggCard__overview">
                <div class="YggCard__overview-about">
                    <component :is="widgetComp" v-bind="exposedProps"></component>
                </div>
            </div>
        </component>
    </article>
</template>
<script>
    import {widgetByType} from './widgets/index';

    export default {
        name:'YggWidget',
        props: {
            widgetType: String,
            widgetProps: Object,
            value: Object
        },
        computed: {
            widgetComp() {
                return widgetByType(this.widgetType, this.widgetProps.display);
            },
            exposedProps() {
                return { ...this.widgetProps, value:this.value }
            },
            hasLink() {
                return !!this.widgetProps.link;
            }
        },
    }
</script>
