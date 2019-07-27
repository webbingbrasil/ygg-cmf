<template>
    <div>
        <h2 v-if="title">{{title}}</h2>
        <ygg-legend :datasets="value.datasets"></ygg-legend>
        <div :class="classes" :style="styles">
            <ygg-chartjs :comp="chartComp" :data="data" :options="options"
                           :styles="{}" cssClasses="YggWidgetChart__inner">
            </ygg-chartjs>
        </div>
    </div>
</template>

<script>
    // Removed because Vue duplication
    import {Bar, Line, Pie} from 'vue-chartjs/es/BaseCharts';
    import YggChartjs from './Chartjs';
    import YggLegend from './Legend';

    const noop = ()=>{};

    export default {
        name:'YggWidgetChart',

        components: {
            YggChartjs,
            YggLegend
        },

        props: {
            display:String,
            title: String,
            value: Object,

            ratioX:Number,
            ratioY:Number,
        },
        computed: {
            classes() {
                return `YggWidgetChart YggWidgetChart--${this.display}`;
            },
            styles() {
                return { paddingTop:`${this.ratioY/this.ratioX*100}%` }
            },
            chartComp() {
                const map = {
                    bar:Bar, line:Line, pie:Pie
                };
                return map[this.display];
            },
            options() {
                return {
                    title: {
                        display: false
                    },
                    legend: {
                        display: false
                    },
                    maintainAspectRatio:false,
                    legendCallback: noop
                }
            },
            data() {
                return {
                    ...this.value,
                    datasets: this.datasets
                }
            },
            datasets() {
                return this.value.datasets.map(dataset=>({
                    ...dataset,
                    ...this.datasetColor(dataset)
                }))
            }
        },
        methods: {
            datasetColor({ color }) {
                return this.display==='line'
                    ? { borderColor: color, fill: false }
                    : { backgroundColor: color };
            }
        },
        mounted() {

        }
    }
</script>
