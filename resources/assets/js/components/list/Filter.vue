<template>
    <div class="YggFilter">
        <component
            :is="filterComp"
            :value="value"
            @input="handleInput"
            v-bind="filter"
        />
    </div>
</template>

<script>
    import {filterByType} from "./filters";

    export default {
        name: 'YggFilter',
        props: {
            filter: {
                type: Object,
                required: true,
            },
            value: [Object, Array, String, Number],
        },
        computed: {
            filterComp() {
                return filterByType(this.filter.type);
            },
        },
        methods: {
            handleInput(value) {
                this.$emit('input', value);
            },
        }
    }
</script>
