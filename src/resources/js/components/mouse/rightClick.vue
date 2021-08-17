<template>
    <!--右鍵選單-->
    <div
        id="rightMenuDom"
        class="right-menu"
        :style="{
            display: rightMenuStatus,
            top: rightMenuTop,
            left: rightMenuLeft,
        }"
    >
        <ul>
            <!--分為2組渲染-->
            <li>
                <span
                    v-for="item in rightMenuList"
                    :key="item.id"
                    v-show="item.id <= 3"
                    @click="item.handler"
                    v-on="customListeners"
                    >{{ item.text }}
                </span>
            </li>
            <li>
                <span
                    v-for="item in rightMenuList"
                    :key="item.id"
                    v-show="item.id > 3"
                    @click="item.handler"
                    v-on="customListeners"
                    >{{ item.text }}
                </span>
            </li>
        </ul>
    </div>
</template>


<script>
export default {
    components: {},
    data() {
        return {};
    },

    mounted() {
        // 監聽全域性點選事件
        document.addEventListener("click", () => {
            // 隱藏右鍵選單
            this.$emit("clicked", this.rightMenuList);
            this.$store.dispatch("RightClick", {
                status: "none",
                left: "0px",
                top: "0px",
                rightMenuList: this.rightMenuList,
            });
        });
    },
    computed: {
        // 右鍵選單顯隱狀態
        rightMenuStatus: function () {
            return this.$store.getters.status;
        },
        // 右鍵選單距離瀏覽器頂部高度
        rightMenuTop: function () {
            return this.$store.getters.top;
        },
        // 右鍵選單距離瀏覽器左邊長度
        rightMenuLeft: function () {
            return this.$store.getters.left;
        },
        // 右鍵選單列表內容
        rightMenuList: function () {
            return this.$store.getters.rightMenuList;
        },
        customListeners: function () {
            // `Object.assign` 将所有的对象合并为一个新对象
            return Object.assign(
                {},
                // 我们从父级添加所有的监听器
                this.$listeners
                // 然后我们添加自定义监听器，或覆写一些监听器的行为
                // this.rightMenuList.handler
                // {
                //     // 这里确保组件配合 `v-model` 的工作
                //     input: function (event) {
                //         // 如果我沒記錯的話，原本預設發射回去父層的附帶值只有event而已
                //         vm.$emit("input", event.target.value);
                //     },
                // }
            );
        },
    },

    methods: {},
};
</script>

<style lang="scss" scoped>
.right-menu {
    position: fixed;
    left: 0;
    top: 0;
    width: 166px;
    height: auto;
    background-color: rgb(242, 242, 242);
    border: solid 1px #c2c1c2;
    box-shadow: 0 10px 10px #c2c1c2;
    display: none;
    border-radius: 5px;

    ul {
        padding: 0;
        margin: 0;
        font-size: 15px;

        li {
            list-style: none;
            box-sizing: border-box;
            padding: 6px 0;
            border-bottom: 1px solid rgb(216, 216, 217);

            &:nth-child(1) {
                padding-top: 2px;
            }

            &:nth-last-child(1) {
                border-bottom: none;
            }

            span {
                display: block;
                height: 20px;
                line-height: 20px;
                padding-left: 16px;

                &:hover {
                    background-color: #0070f5;
                    cursor: pointer;
                    color: #ffffff;
                }
            }
        }
    }
}
</style>