<script>
    import { ignoreWarns } from '../../util';
    import Pagination from 'bootstrap-vue/es/components/pagination/pagination';

    // for props/events check
    // https://bootstrap-vue.js.org/docs/components/pagination

    export default {
        name: 'YggPagination',
        functional: true,

        render(h,ctx) {
            ctx.data.staticClass = 'YggPagination';

            return h({
                name:'YggPagination',
                extends: Pagination,

                watch: {
                    numberOfPages: {
                        immediate:true,
                        handler(n) {
                            if(!ctx.props.minPageEndButtons)return;
                            // Hide first/last buttons if number of pages inf than 3
                            ignoreWarns(() => this.hideGotoEndButtons = n<ctx.props.minPageEndButtons);
                        }
                    }
                }
            }, ctx.data, ctx.children);
        }
    }
</script>