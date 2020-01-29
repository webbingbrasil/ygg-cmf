<template>
    <div class="YggTabbedLayout">
        <template v-if="showTabs">
            <YggTabs>
                <template slot="nav-prepend"><slot name="nav-prepend"></slot></template>
                <YggTab v-for="(tab,i) in layout.tabs" :title="tab.title" :key="i">
                    <slot v-bind="tab"></slot>
                </YggTab>
            </YggTabs>
        </template>
        <template v-else>
            <div><slot name="nav-prepend"></slot></div>
            <div v-for="tab in layout.tabs">
                <slot v-bind="tab"></slot>
            </div>
        </template>
    </div>
</template>

<script>
    import YggTabs from './Tabs';
    import YggTab from './Tab';

    export default {
        name:'YggTabbedLayout',
        props : {
            layout: Object,
        },
        provide() {
            if(!this.showTabs) {
                return { $tab: false }
            }
        },
        components: {
            YggTabs,
            YggTab,
        },
        computed: {
            showTabs() {
                return this.layout.tabbed && this.layout.tabs.length>1;
            }
        }
    }
</script>
