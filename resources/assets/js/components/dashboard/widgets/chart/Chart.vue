<template>
    <div>
        <template v-if="title">
            <h2 class="mb-2">{{title}}</h2>
        </template>
        <YggChartLegend :datasets="value.datasets"/>
        <div :class="classes" :style="style">
            <component :chart-data="chartData" :is="chartComp" class="YggWidgetChart__inner"/>
        </div>
    </div>
</template>

<script>
    import {getChartByType, transformData} from './index';
    import YggChartLegend from './Legend';

    export default {
        name: 'YggWidgetChart',
        components: {
            YggChartLegend,
        },
        props: {
            display: String,
            title: String,
            value: Object,
            ratioX: Number,
            ratioY: Number,
        },
        computed: {
            chartComp() {
                return getChartByType(this.display);
            },
            chartData() {
                return transformData(this.display, this.value);
            },
            classes() {
                return `YggWidgetChart YggWidgetChart--${this.display}`;
            },
            style() {
                return {
                    'padding-top': `${this.ratioY / this.ratioX * 100}%`,
                }
            },
        },
    }
</script>
