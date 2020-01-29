<template>
    <div class="YggDropdown"
         :class="{'YggDropdown--open':opened,
         'YggDropdown--disabled':disabled,
         'YggDropdown--above':isAbove,
         'YggDropdown--no-arrow':!showArrow}"
         :tabindex="disabled?-1:0"
         @focus="handleFocus" @blur="handleBlur">
        <div class="YggDropdown__text" @mousedown="toggleIfFocused">
            <slot name="text">{{text}}</slot>
        </div>
        <dropdown-arrow v-if="showArrow" class="YggDropdown__arrow"></dropdown-arrow>
        <div v-if="!disabled">
            <ul class="YggDropdown__list" ref="list">
                <slot></slot>
            </ul>
        </div>
    </div>
</template>

<script>
    import DropdownArrow from './Arrow';

    export default {
        name: 'YggDropdown',
        components: {
            DropdownArrow
        },
        provide() {
            return  {
                $dropdown: this
            }
        },
        props: {
            text: String,
            showArrow: {
                type: Boolean,
                default: true
            },
            disabled: Boolean
        },
        data() {
            return {
                opened: false,
                isAbove: false
            }
        },
        watch: {
            opened(val) {
                if(val) {
                    this.$nextTick(_=> this.$emit('opened'));
                }
            }
        },
        methods:{
            async handleFocus() {
                if(this.disabled) return;
                this.opened = true;
                await this.$nextTick();
                this.adjustPosition();
            },
            handleBlur() {
                this.opened = false;
                this.isAbove = false;
            },
            toggleIfFocused(e) {
                if(this.opened) {
                    this.$el.blur();
                    e.preventDefault();
                }
            },
            adjustPosition () {
                let { bottom } = this.$refs.list.getBoundingClientRect();
                this.isAbove = bottom > window.innerHeight;
            }
        },
        mounted() {
        }
    }
</script>