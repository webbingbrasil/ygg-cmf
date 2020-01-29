<template>
    <transition-group :enter-active-class="enterActiveClass"
                      :leave-active-class="transitionClass"
                      @after-enter="afterEnter"
                      @after-leave="afterLeave"
                      @before-enter="beforeEnter"
                      @enter="enter"
                      @leave="leave">
        <div key="0" style="position:absolute" v-show="active===0">
            <slot :next="increase" name="frame-0"></slot>
        </div>
        <div key="1" style="position:absolute" v-show="active===1">
            <slot :next="increase" name="frame-1"></slot>
        </div>
    </transition-group>
</template>

<script>
    const noop = () => {
    };
    export default {
        props: {
            transitionClass: String,
        },
        data() {
            return {
                active: 0,
                width: 0,
                enteringElm: null,
                leavingElm: null,
                afterEnterCallback: noop
            }
        },
        computed: {
            enterActiveClass() {
                return `${this.transitionClass} collapse-enter-active`;
            }
        },
        methods: {
            increase(callback = noop) {
                this.active = (this.active + 1) % 2;
                this.afterEnterCallback = callback;
            },
            beforeLeave(e) {
            },
            leave(e) {
                setTimeout(() => e.style.width = `${this.width}px`, 100);
            },
            afterLeave(e) {
                e.style.width = "";
                this.enteringElm.style.visibility = '';
                this.afterEnterCallback();
            },
            beforeEnter(e) {
            },
            enter(e) {
                e.style.visibility = 'hidden';
                this.width = e.scrollWidth;
                this.enteringElm = e;
            },
            afterEnter(e) {
            }
        }
    }
</script>
