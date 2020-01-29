<template>
    <div class="YggDataList__row container"
        :class="[
            header ? 'YggDataList__row--header': '',
            !header && !hasLink ? 'YggDataList__row--disabled' : '',
            rowClasses(row)
        ]"
    >
        <div class="YggDataList__cols">
            <div class="row">
                <template v-for="column in columns">
                    <div :class="[
                        header ? 'YggDataList__th' : 'YggDataList__td',
                        colClasses(column)
                    ]">
                        <slot name="cell" :row="row" :column="column">
                            <template v-if="column.html">
                                <div v-html="row[column.key]" class="YggDataList__td-html-container"></div>
                            </template>
                            <template v-else>
                                {{ row[column.key] }}
                            </template>
                        </slot>
                    </div>
                </template>
            </div>
            <template v-if="hasLink">
                <a class="YggDataList__row-link" :href="url"></a>
            </template>
        </div>
        <template v-if="$slots.append">
            <div class="YggDataList__row-append align-self-center">
                <slot name="append" />
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            columns: Array,
            row: {
                type: Object,
                default: ()=>({})
            },
            url: String,
            header: Boolean
        },
        computed: {
            hasLink() {
                return !!this.url;
            }
        },
        methods: {
            rowClasses(row) {
                if("rowClass" in row) {
                    return row['rowClass']
                }

                return [];
            },
            colClasses(column) {
                if(column.size === 0) {
                    return [
                        `col`,
                        ...(column.hideOnXS ? ['d-none d-md-flex'] : []),
                        ...(column.customClasses),
                        ...(column.classes)
                    ];
                }

                return [
                    `col-${column.sizeXS}`,
                    `col-md-${column.size}`,
                    ...(column.hideOnXS ? ['d-none d-md-flex'] : []),
                    ...(column.classes)
                ];
            },
        }
    }
</script>