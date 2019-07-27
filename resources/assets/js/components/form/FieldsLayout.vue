<template>
    <ygg-grid :rows="layout">
        <template slot-scope="fieldLayout">
            <slot v-if="!fieldLayout.legend" v-bind="fieldLayout"></slot>

            <fieldset class="YggForm__fieldset" v-else v-show="isFieldsetVisible(fieldLayout)">
                <div class="YggModule__inner">
                    <div class="YggModule__header">
                        <legend class="YggModule__title">{{fieldLayout.legend}}</legend>
                    </div>
                    <div class="YggModule__content">
                        <ygg-fields-layout :layout="fieldLayout.fields">
                            <template slot-scope="fieldset">
                                <slot v-bind="fieldset"></slot>
                            </template>
                        </ygg-fields-layout>
                    </div>
                </div>
            </fieldset>
        </template>
    </ygg-grid>
</template>

<script>
    import YggGrid from '../Grid';

    export default {
        name:'YggFieldsLayout',

        components: {
            YggGrid
        },

        props: {
            layout: { // 2D array fields [ligne][col]
                type: Array,
                required: true
            },
            visible : {
                type: Object,
                default: () => ({})
            }
        },

        data() {
            return {
                fieldsetMap: {}
            }
        },

        methods: {
            isFieldsetVisible(fieldLayout) {
                let { id, fields } = fieldLayout;

                let map = this.fieldsetMap[id] || (this.fieldsetMap[id] = [].concat.apply([],fields));
                return map.some(f => this.visible[f.key]);
            }
        }
    }
</script>