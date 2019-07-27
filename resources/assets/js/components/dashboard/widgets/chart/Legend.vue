<template>
    <div class="YggLegend">
        <div class="YggLegend__list row">
            <div v-for="dataset in datasets" class="YggLegend__item col-sm">
                <span class="YggLegend__color" :style="{background:dataset.color}"></span>
                <span class="YggLegend__text">{{dataset.label}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name:'YggLegend',
        props: {
            datasets: Array
        }
    }
</script>