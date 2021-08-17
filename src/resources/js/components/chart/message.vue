<template>
    <div class="message-form" v-scroll-bottom.sync="sessions">
        <template v-for="item in sessions">
            <!-- other people -->
            <template v-if="item.push == 1">
                <div class="messageBox">
                    <img
                        :src="def_avatar"
                        class="messageBox__avatar"
                        width="40"
                        height="40"
                        draggable="false"
                    />
                    <div class="messageBox__content">
                        <div class="messageBox__name">{{other.name}}</div>
                        <div class="messageBox__message messageBox__text">{{item.content}}</div>
                    </div>
                    <div class="messageBox__time">{{item.date }}</div>
                </div>
            </template>
            <!-- self -->
            <template v-if="item.push == 0">
                <div class="messageBox messageBox--self">
                    <div class="messageBox__time messageBox__time-self">{{item.date }}</div>
                    <div class="messageBox__content">
                        <div
                            class="messageBox__message messageBox__message-self messageBox__text messageBox__text-self"
                        >{{item.content}}</div>
                    </div>
                </div>
            </template>
        </template>
        <!-- <ul v-if="session">
            <li v-for="item in session.messages">
                <p class="time">
                    <span>{{ item.date | timeFormat }}</span>
                </p>
                <div>
                    <img class="avatar" width="30" height="30" :src="def_avatar" />
                    <div class="text">{{ item.content }}</div>
                </div>
            </li>
        </ul>-->
    </div>
</template>
<script>
export default {
    data: function () {
        return {
            def_avatar: "images/img_profile_default.png",
        };
    },
    computed: {
        user() {
            return this.$store.getters.user;
        },
        other() {
            let list = this.$store.getters.list;
            let currentId = this.$store.getters.currentSessionId;
            return list.find(({ parent_id }) => parent_id === currentId);
        },
        sessions() {
            let sessions = this.$store.getters.sessions;
            // let session = sessions.find(({ id }) => id == currentSessionId);
            console.log("session", sessions);
            // console.log("date", session.messages[0].content);
            // return sessions.reverse();
            return sessions;
        },
    },

    filters: {
        // 将日期過濾成 MM:DD hour:minutes
        timeFormat(date) {
            console.log("date: ", date);
            if (typeof date === "string") {
                date = new Date(date);
                console.log("date111: ", date);
            }
            return (
                date.getMonth() +
                1 +
                "/" +
                date.getDate() +
                " " +
                date.getHours() +
                ":" +
                date.getMinutes()
            );
        },
    },
    directives: {
        // "infinit-scroll": {
        //     inserted: function (el, binding) {
        //         let f = function (evt) {
        //             if (binding.value(evt, el)) {
        //                 window.removeEventListener("scroll", f);
        //             }
        //         };
        //         window.addEventListener("scroll", f);
        //     },
        // },
        // 发送消息后滚动到底部
        "scroll-bottom": {
            bind: function (el) {
                el.scrollTop = el.scrollHeight - el.clientHeight;
            },
            inserted: function (el) {
                el.scrollTop = el.scrollHeight - el.clientHeight;
            },
            update: function (el) {
                el.scrollTop = el.scrollHeight - el.clientHeight;
            },
        },
    },
};
</script>


<style lang="scss" scoped>
.message-form {
    height: 100%;
    // position: relative;
    padding: 10px 15px;
    overflow-y: scroll;
    transform: translateZ(0);
    // li {
    //     margin-bottom: 15px;
    // }
}
.messageBox {
    padding: 5px 10px;
    position: relative;
}
.messageBox__user {
    height: 40px;
    width: 40px;
    border-radius: 50%;
    vertical-align: top;
    display: inline-block;
    cursor: pointer;
}
.messageBox__content {
    max-width: 70%;
    display: inline-block;
}
.messageBox__avatar {
    vertical-align: top;
    border-radius: 5px;
}
.messageBox__name {
    position: relative;
    margin: 5px 0px 5px 5px;
    font-size: 13px;
    color: #727c8a;
    vertical-align: top;
    cursor: pointer;
}
.messageBox__message {
    margin: 5px 0px 5px 5px;
    font-size: 12px;
    color: #35393d;
    letter-spacing: 0.6px;
    background-color: #bec1c2;
    border-radius: 10px;
    line-height: 1.5;
    text-align: left;
    word-break: break-all;
    /*：與html的<pre></pre>同效果，可以使textarea的換行元素正常顯示 */
    white-space: pre-line;
}
.messageBox__text {
    padding: 8px 10px 9px 11px;
    max-height: 300px;
    overflow: hidden;
}
.messageBox__time {
    transform: scale(0.7);
    color: #acb0b8;
    vertical-align: bottom;
    margin: 0px 0px 5px -8px;
    display: inline-block;
}
.messageBox--self {
    text-align: right;

    .messageBox__message-self {
        background-color: #aff47e;
        margin-right: 10px;
    }
    .messageBox__text-self {
        overflow: unset;
    }
    .messageBox__time-self {
        margin: 0px -13px 0px 0px;
    }
}
// .time {
//     margin: 7px 0;
//     text-align: center;

//     > span {
//         display: inline-block;
//         padding: 0 18px;
//         font-size: 12px;
//         color: #fff;
//         border-radius: 2px;
//         background-color: #dcdcdc;
//     }
// }
// .avatar {
//     float: left;
//     margin: 0 10px 0 0;
//     border-radius: 3px;
// }
// .text {
//     display: inline-block;
//     position: relative;
//     padding: 0 10px;
//     max-width: "calc(100% - 40px)";
//     min-height: 30px;
//     line-height: 2.5;
//     font-size: 12px;
//     text-align: left;
//     word-break: break-all;
//     background-color: #fafafa;
//     border-radius: 4px;

//     &:before {
//         content: " ";
//         position: absolute;
//         top: 9px;
//         right: 100%;
//         border: 6px solid transparent;
//         border-right-color: #fafafa;
//     }
// }

// .self {
//     text-align: right;

//     .avatar {
//         float: right;
//         margin: 0 0 0 10px;
//     }
//     .text {
//         background-color: #b2e281;

//         &:before {
//             right: inherit;
//             left: 100%;
//             border-right-color: transparent;
//             border-left-color: #b2e281;
//         }
//     }
// }
</style>