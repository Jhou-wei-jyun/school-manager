<template>
    <div
        class="context-menu"
        ref="popper"
        v-show="isVisible"
        v-click-outside="close"
        tabindex="-1"
        @contextmenu.capture.prevent
    >
        <ul>
            <slot :contextData="contextData" />
        </ul>
    </div>
</template>

<script>
import Popper from "popper.js";
import ClickOutside from "vue-click-outside";
// @vue/component
export default {
    props: {
        boundariesElement: {
            type: String,
            default: "body",
        },
        navWidth: {
            type: Number,
            default: 0,
        },
    },
    components: {
        Popper,
    },
    data() {
        return {
            opened: false,
            contextData: {},
        };
    },
    directives: {
        ClickOutside,
    },
    computed: {
        isVisible() {
            return this.opened;
        },
    },

    methods: {
        open(evt, contextData) {
            this.opened = true;
            this.contextData = contextData;
            this.$emit("targetChange", this.contextData);
            if (this.popper) {
                this.popper.destroy();
            }
            console.log(document.querySelectorAll(this.boundariesElement));
            this.popper = new Popper(
                this.referenceObject(evt),
                this.$refs.popper,
                {
                    placement: "right-start",
                    modifiers: {
                        preventOverflow: {
                            boundariesElement: document.querySelector(
                                this.boundariesElement
                            ),
                        },
                    },
                }
            );

            // Recalculate position
            this.$nextTick(() => {
                this.popper.scheduleUpdate();
            });
        },
        close() {
            this.opened = false;
            this.contextData = null;
            // this.$emit("targetChange", this.contextData);
        },
        referenceObject(evt) {
            const left = evt.clientX - this.navWidth;
            const top = evt.clientY;
            const right = left + 1;
            const bottom = top + 1;
            const clientWidth = 0;
            const clientHeight = 0;

            function getBoundingClientRect() {
                return {
                    left,
                    top,
                    right,
                    bottom,
                };
            }

            const obj = {
                getBoundingClientRect,
                clientWidth,
                clientHeight,
            };
            return obj;
        },
    },
    beforeDestroy() {
        if (this.popper !== undefined) {
            this.popper.destroy();
        }
    },
};
</script>

<style lang="scss" scoped>
.context-menu {
    position: fixed;
    z-index: 999;
    overflow: hidden;
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 4px 0 #eee;

    &:focus {
        outline: none;
    }

    ul {
        padding: 0px;
        margin: 0px;
    }
}
</style>
