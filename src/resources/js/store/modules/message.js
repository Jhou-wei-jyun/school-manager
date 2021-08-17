/*
  這邊可以改為用 types 物件取代 matutions_type.js (繼續用也可以)
  然後在前面加上模組名稱作為前綴，用來避免與其他模組重複。
  因為 action、mutation、和 getter 依然是註冊在全域的命名空間
*/
const types = {
    INIT_DATA: 'message/INIT_DATA',
    SEND_MESSAGE: 'message/SEND_MESSAGE',
    SELECT_SESSION: 'message/SELECT_SESSION',
    OPEN_CHAT: 'message/OPEN_CHAT',
    DELETE_SESSION: 'message/DELETE_SESSION',
    SEARCH_LIST: 'message/SEARCH_LIST',
}

// count state 必須是 Object
const state = {
    time: true,
    school: sessionStorage.school,
    // 當前用户
    user: {
        id: sessionStorage.id,
        name: sessionStorage.name,
        img: sessionStorage.avatar,
    },
    list: [],
    list_show: [],

    sessions: [],
    // 當前選擇對象
    currentSessionId: null,
    filterKey: '',

}

// getters 也可以整理到這邊直接返回 count 內容
const getters = {
    user: state => {
        return state.user;
    },
    filterKey: state => {
        return state.filterKey;
    },
    sessions: state => {
        return state.sessions;
    },
    currentSessionId: state => {
        return state.currentSessionId;
    },
    list: state => {
        return state.list;
    },
    list_show: state => {
        return state.list_show;
    },
}

// actions 也是以 Object 形式建構。
const actions = {
    initdataAction({ commit }) {
        commit(types.INIT_DATA);
    },
    sendmessageAction({ commit }, content) {
        commit(types.SEND_MESSAGE, content);
    },
    selectAction({ commit }, id) {
        commit(types.SELECT_SESSION, id);
        commit(types.OPEN_CHAT);
    },
    deletesessionAction({ commit }) {
        commit(types.DELETE_SESSION);
    },
    searchlistAction({ commit }, n) {
        commit(types.SEARCH_LIST, n);
    },
}

// mutations
const mutations = {
    [types.INIT_DATA](state) {
        axios
            .post('chatlist', { school_id: state.school })
            .then((response) => {
                // state.sessions = [];
                for (var i = 0; i < response.data.parent.length; i++) {
                    state.list.push({

                        name: response.data.parent[i].name,
                        parent_id: response.data.parent[i].parent_id,

                    });

                }
                // let count = response.data.user.length;
                // for (var i = 0; i < response.data.parent.length; i++) {
                //     state.list.push({
                //         id: i + count,
                //         user: {
                //             name: response.data.parent[i].name,
                //             img: response.data.parent[i].avatar,
                //             parent_id: response.data.parent[i].parent_id,
                //         }
                //     });

                // }
                state.list_show = state.list;
                console.log('state?', state.list_show);
            })
    },
    [types.SEND_MESSAGE](state, content) {
        let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
        axios
            .post('sendmessage', {
                sender_id: state.user.id,
                sender: state.user.name,
                to: current.name,
                message: content,
                //status: 0->send, 1->request
                status: 0,
                school_id: state.school,
                parent_id: current.parent_id,
                // user_id: current.user.user_id,
            })
            // .then((response) => {
            //     console.log('res', response.data.length);
            //     state.sessions.push(
            //         {
            //             content: response.data.message,
            //             date: response.data.created_at,
            //             push: response.data.status,
            //         }
            //     );
            //     console.log('message', state.sessions);
            // })
            .catch((error) => { console.log(error) })
            .finally(() => {
            });
    },
    [types.SELECT_SESSION](state, id) {
        // if (state.time) {
        //     clearInterval(state.time);
        //     console.log('clear');
        // }

        state.currentSessionId = id;
        let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
        // axios
        //     .post('getmessage', {
        //         sender: state.user.name,
        //         sender_id: state.user.id,
        //         // user_id: current.user.user_id,
        //         parent_id: current.parent_id,
        //     })
        //     .then((response) => {
        //         // console.log('res', response.data.length);
        //         state.sessions = [];
        //         for (var i = 0; i < response.data.length; i++) {
        //             state.sessions.push(
        //                 {
        //                     content: response.data[i].message,
        //                     date: response.data[i].created_at,
        //                     push: response.data[i].status,
        //                 }
        //             );

        //         }
        //         // if (current.user.user_id != null) {
        //         //     axios
        //         //         .post('private-event', {
        //         //             user_id: current.user.user_id,
        //         //         })
        //         // }
        //         // if (current.user.parent_id != null) {
        //         //     axios
        //         //         .post('private-event', {
        //         //             parent_id: current.user.parent_id
        //         //         })
        //         // }
        //         // state.time = setInterval(function () {
        //         //     axios
        //         //         .post('getmessage', {
        //         //             sender: state.user.name,
        //         //             user_id: current.user.user_id,
        //         //             parent_id: current.user.parent_id,
        //         //         })
        //         //         .then((response) => {
        //         //             // console.log('res', response.data.length);
        //         //             state.sessions = [];
        //         //             for (var i = 0; i < response.data.length; i++) {
        //         //                 state.sessions.push(
        //         //                     {
        //         //                         content: response.data[i].message,
        //         //                         date: response.data[i].created_at,
        //         //                         push: response.data[i].status,
        //         //                     }
        //         //                 );

        //         //             }
        //         //             // console.log('message', state.sessions);
        //         //         })
        //         //         .catch((error) => { console.log(error) });
        //         // }, 3000);

        //         // console.log('message', state.sessions);
        //         //             window.Echo.channel('private-event.${}')
        //         //    .listen('TaskAdded',function(data){
        //         // 	  console.log('received a message');
        //         // 	  console.log(data);
        //         // 	});
        //     })
        //     .catch((error) => { console.log(error) });
    },
    [types.OPEN_CHAT](state) {
        let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
        // if (current.user.parent_id != null) {
        //     // Start socket.io listener
        //     Echo.channel('laravel_database_message-' + current.user.name + '-' + current.user.user_id + '-' + state.user.name + '-' + state.user.id)
        //         .listen('Message', (data) => {
        //             if (state.user.name) {
        //                 state.sessions.push(
        //                     {
        //                         content: data.message.message,
        //                         date: data.message.created_at,
        //                         push: data.message.status,
        //                     }
        //                 )
        //             }
        //         })
        //     // End socket.io listener
        // }
        if (current.parent_id != null) {
            Echo.join('message-253')
                // Echo.join('message-' + current.parent_id + '-' + state.user.id)
                .here((members) => {
                    console.log('目前此頻道所有使用者有：');
                    console.log(typeof members);
                    console.log(members);
                })
                .joining((joiningMember) => {
                    console.log('有一位使用者加入了頻道')
                    console.log(joiningMember);
                })
                .leaving((leavingMember) => {
                    console.log('有一位使用者離開了頻道')
                    console.log(leavingMember);
                })
                .listen('Message', (data) => {
                    console.log('收到 Message 事件包');
                    console.log(data);
                });
            // Start socket.io listener
            // console.log('success');
            // Echo.private('activities.1')
            //     .listen('Message', (e) => {
            //         console.log(e);
            //     });

            // // End socket.io listener
        }
    },
    [types.DELETE_SESSION]() {
        localStorage.clear();
    },
    [types.SEARCH_LIST](state, n) {
        // if (n === null || n === '') {
        //     state.list_show = state.list;
        // }
        state.list_show = state.list.filter(({ name }) => name.includes(n));

    },

}

/*
  因為我們把 vuex 所有職權都寫在同一隻檔案，
  所以必須要 export 出去給最外層 index.js 組合使用
*/
export default {
    state,
    getters,
    actions,
    mutations
}