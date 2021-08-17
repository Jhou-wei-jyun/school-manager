<template>
    <div class="text">
        <textarea placeholder="按 Enter 發送" v-model="content" @keydown.enter="onKeyup"></textarea>
    </div>
</template>
<script>
// import { actions } from "../../store";

export default {
    data() {
        return {
            content: "",
        };
    },
    methods: {
        onKeyup(e) {
            if (e.shiftKey) {
                return false;
            }
            if (this.content.length < 1) {
                // 避免enter產生的空白換行
                e.preventDefault();
                return false;
            }
            this.$store.dispatch("sendmessageAction", this.content);
            this.content = "";
        },
    },
};
</script>

<style lang="scss" scoped>
.text {
    height: 100%;
    border-top: solid 1px #ddd;
    overflow: hidden;

    textarea {
        padding: 10px;
        height: 100%;
        width: 100%;
        border: none;
        outline: none;
        font-family: "Micrsofot Yahei";
        resize: none;
    }
}
</style>