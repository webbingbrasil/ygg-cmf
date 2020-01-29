<template>
    <div :class="classes" class="YggFilterDateRange">
        <YggFilterControl :label="label" :no-caret="noCaret" :opened="opened" @click="handleClicked">
            <YggDateRange
                :clearable="!required"
                :display-format="displayFormat"
                :monday-first="mondayFirst"
                :value="value"
                @blur="handlePickerBlur"
                @focus="handlePickerFocused"
                @input="handleInput"
                class="YggFilterDateRange__field"
                ref="range"
            />
        </YggFilterControl>
    </div>
</template>

<script>
    import YggFilterControl from '../FilterControl';
    import YggDateRange from '../../form/fields/date-range/DateRange';

    export default {
        name: 'YggFilterDateRange',
        components: {
            YggDateRange,
            YggFilterControl,
        },
        props: {
            value: {
                required: true,
            },
            required: Boolean,
            displayFormat: String,
            mondayFirst: Boolean,
            label: String,
        },
        data() {
            return {
                opened: false,
            }
        },
        computed: {
            empty() {
                return !this.value;
            },
            noCaret() {
                return !!this.value && !this.required;
            },
            classes() {
                return {
                    'YggFilterDateRange--empty': this.empty,
                }
            },
        },
        methods: {
            handleClicked() {
                this.$refs.range.$refs.picker.focus();
            },
            handleInput(range) {
                this.$emit('input', range);
            },
            handlePickerFocused() {
                this.opened = true;
            },
            handlePickerBlur() {
                this.opened = false;
            },
        }
    }
</script>
