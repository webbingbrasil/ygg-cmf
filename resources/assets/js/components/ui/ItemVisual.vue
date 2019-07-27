<template>
    <span>
        <i v-if="item.icon" class="fa" :class="iconClasses"></i>
        <img v-else-if="item.image" :src="item.image">
    </span>
</template>

<script>
    export default {
        name:'YggItemVisual',
        props: {
            item: Object,
            iconClass: String,
            imageClass: String
        },
        computed: {
            iconClasses() {
                let classes = [this.item.icon];
                this.iconClass && classes.push(this.iconClass);
                return classes;
            }
        }
    }
</script>