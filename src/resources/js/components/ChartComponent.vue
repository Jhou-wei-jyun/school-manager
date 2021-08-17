<template>
    <div id="app">
        <div class="side">
            <Card></Card>
        </div>
        <div class="listbar">
            <List></List>
        </div>
        <div class="main">
            <Message></Message>
        </div>
        <div class="text">
            <Charttext></Charttext>
        </div>
        <b-input class="test" v-model="content"></b-input>
        <b-button class="btn" @click="testsend()">Send</b-button>
    </div>
</template>

<script>
import Card from "./chart/card";
import List from "./chart/list";
import Charttext from "./chart/text";
import Message from "./chart/message";

export default {
    components: {
        Card,
        List,
        Charttext,
        Message,
    },
    data: function () {
        return {
            content: null,
        };
    },
    // vuex: {
    //     actions: actions,
    // },
    beforeCreate() {
        this.$store.dispatch("initdataAction");
    },
    methods: {
        testsend() {
            axios
                .post("testsend", {
                    sender_id: 1,
                    sender: "admin",
                    to: "Luna",
                    message: this.content,
                    //status: 0->send, 1->request
                    status: 1,
                    school_id: 3,
                    parent_id: 8,
                })
                .finally(() => {
                    this.content = null;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
#app {
    margin: 0 auto;
    width: 800px;
    height: 80vh;
    overflow: hidden;
    display: grid;
    grid-template:
        "card  card  main  main" 15vh
        "list  list  main  main" 35vh
        "list  list  text  text" 20vh
        "test   btn   ...   ... " 10vh
        /100px 100px 300px 300px;
    // border-radius: 3px;

    .side {
        grid-area: card;
        background-color: lightcoral;
    }
    .listbar {
        grid-area: list;
        background-color: rgb(223, 219, 219);
    }
    .main {
        grid-area: main;
        background-color: whitesmoke;
    }
    .text {
        grid-area: text;
        background-color: rgb(80, 47, 47);
    }
    .test {
        grid-area: test;
    }
    .btn {
        grid-area: btn;
    }
    // .text {
    //     position: absolute;
    //     width: 100%;
    //     bottom: 0;
    //     left: 0;
    // }
    // .message {
    //     height: "calc(100% - 160px)";
    //     // overflow: hidden;
    // }
}
</style>
