<template>
    <textarea :disabled="readOnly" :placeholder="placeholder" :rows="rows" :value="value"
              @input="handleInput" class="YggTextarea" v-maxlength="maxLength">
    </textarea>
</template>

<script>
    import Maxlength from '../../../directives/Maxlength';

    export default {
        name: 'YggTextarea',

        props: {
            value: {
                type: String,
            },
            placeholder: String,
            readOnly: Boolean,

            maxLength: Number,

            rows: Number,
        },
        data() {
            return {}
        },
        methods: {
            handleInput(e) {
                this.$emit('input', e.target.value);
            }
        },
        directives: {
            Maxlength
        }
    }
</script>
