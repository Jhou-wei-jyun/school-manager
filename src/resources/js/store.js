// /**
//  * Vuex
//  * http://vuex.vuejs.org/zh-cn/intro.html
//  */
// import Vue from 'vue';
// import Vuex from 'vuex';
// import axios from 'axios';


// Vue.use(Vuex);

// const now = new Date();
// const store = new Vuex.Store({
//     state: {
//         time: true,
//         school: sessionStorage.school,
//         // 当前用户
//         user: {
//             id: sessionStorage.id,
//             name: sessionStorage.name,
//             img: sessionStorage.avatar,
//         },
//         list: [],
//         list_show: [],

//         sessions: [],
//         // 當前選擇對象
//         currentSessionId: null,
//         filterKey: ''
//     },
//     getters: {
//         // school: state => {
//         //     return state.school;
//         // },
//         user: state => {
//             return state.user;
//         },
//         filterKey: state => {
//             return state.filterKey;
//         },
//         sessions: state => {
//             return state.sessions;
//         },
//         currentSessionId: state => {
//             return state.currentSessionId;
//         },
//         list: state => {
//             return state.list;
//         },
//         list_show: state => {
//             return state.list_show;
//         },
//     },
//     mutations: {
//         INIT_DATA(state) {
//             axios
//                 .post('chatlist', { school_id: state.school })
//                 .then((response) => {
//                     // state.sessions = [];
//                     for (var i = 0; i < response.data.parent.length; i++) {
//                         state.list.push({

//                             name: response.data.parent[i].name,
//                             parent_id: response.data.parent[i].parent_id,

//                         });

//                     }
//                     // let count = response.data.user.length;
//                     // for (var i = 0; i < response.data.parent.length; i++) {
//                     //     state.list.push({
//                     //         id: i + count,
//                     //         user: {
//                     //             name: response.data.parent[i].name,
//                     //             img: response.data.parent[i].avatar,
//                     //             parent_id: response.data.parent[i].parent_id,
//                     //         }
//                     //     });

//                     // }
//                     state.list_show = state.list;
//                 })
//         },
//         // 发送消息
//         SEND_MESSAGE(state, content) {
//             let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
//             axios
//                 .post('sendmessage', {
//                     sender_id: state.user.id,
//                     sender: state.user.name,
//                     to: current.name,
//                     message: content,
//                     //status: 0->send, 1->request
//                     status: 0,
//                     school_id: state.school,
//                     parent_id: current.parent_id,
//                     // user_id: current.user.user_id,
//                 })
//                 // .then((response) => {
//                 //     console.log('res', response.data.length);
//                 //     state.sessions.push(
//                 //         {
//                 //             content: response.data.message,
//                 //             date: response.data.created_at,
//                 //             push: response.data.status,
//                 //         }
//                 //     );
//                 //     console.log('message', state.sessions);
//                 // })
//                 .catch((error) => { console.log(error) })
//                 .finally(() => {
//                 });
//         },
//         // 選擇對象
//         SELECT_SESSION(state, id) {

//             // if (state.time) {
//             //     clearInterval(state.time);
//             //     console.log('clear');
//             // }

//             state.currentSessionId = id;
//             let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
//             axios
//                 .post('getmessage', {
//                     sender: state.user.name,
//                     sender_id: state.user.id,
//                     // user_id: current.user.user_id,
//                     parent_id: current.parent_id,
//                 })
//                 .then((response) => {
//                     // console.log('res', response.data.length);
//                     state.sessions = [];
//                     for (var i = 0; i < response.data.length; i++) {
//                         state.sessions.push(
//                             {
//                                 content: response.data[i].message,
//                                 date: response.data[i].created_at,
//                                 push: response.data[i].status,
//                             }
//                         );

//                     }
//                     // if (current.user.user_id != null) {
//                     //     axios
//                     //         .post('private-event', {
//                     //             user_id: current.user.user_id,
//                     //         })
//                     // }
//                     // if (current.user.parent_id != null) {
//                     //     axios
//                     //         .post('private-event', {
//                     //             parent_id: current.user.parent_id
//                     //         })
//                     // }
//                     // state.time = setInterval(function () {
//                     //     axios
//                     //         .post('getmessage', {
//                     //             sender: state.user.name,
//                     //             user_id: current.user.user_id,
//                     //             parent_id: current.user.parent_id,
//                     //         })
//                     //         .then((response) => {
//                     //             // console.log('res', response.data.length);
//                     //             state.sessions = [];
//                     //             for (var i = 0; i < response.data.length; i++) {
//                     //                 state.sessions.push(
//                     //                     {
//                     //                         content: response.data[i].message,
//                     //                         date: response.data[i].created_at,
//                     //                         push: response.data[i].status,
//                     //                     }
//                     //                 );

//                     //             }
//                     //             // console.log('message', state.sessions);
//                     //         })
//                     //         .catch((error) => { console.log(error) });
//                     // }, 3000);

//                     // console.log('message', state.sessions);
//                     //             window.Echo.channel('private-event.${}')
//                     //    .listen('TaskAdded',function(data){
//                     // 	  console.log('received a message');
//                     // 	  console.log(data);
//                     // 	});
//                 })
//                 .catch((error) => { console.log(error) });
//         },
//         OPEN_CHAT(state) {
//             let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
//             // if (current.user.parent_id != null) {
//             //     // Start socket.io listener
//             //     Echo.channel('laravel_database_message-' + current.user.name + '-' + current.user.user_id + '-' + state.user.name + '-' + state.user.id)
//             //         .listen('Message', (data) => {
//             //             if (state.user.name) {
//             //                 state.sessions.push(
//             //                     {
//             //                         content: data.message.message,
//             //                         date: data.message.created_at,
//             //                         push: data.message.status,
//             //                     }
//             //                 )
//             //             }
//             //         })
//             //     // End socket.io listener
//             // }
//             if (current.parent_id != null) {
//                 // Start socket.io listener
//                 Echo.channel('laravel_database_message-' + current.parent_id + '-' + state.user.id)
//                     .listen('Message', (data) => {
//                         if (state.user.name) {
//                             state.sessions.push(
//                                 {
//                                     content: data.message.message,
//                                     date: data.message.created_at,
//                                     push: data.message.status,
//                                 }
//                             )
//                         }
//                     })
//                 // End socket.io listener
//             }
//         },
//         // 搜索
//         // SET_FILTER_KEY(state, value) {
//         //     state.filterKey = value;
//         // },
//         DELETE_SESSION() {
//             localStorage.clear();
//         },
//         SEARCH_LIST(state, n) {
//             // if (n === null || n === '') {
//             //     state.list_show = state.list;
//             // }
//             state.list_show = state.list.filter(({ name }) => name.includes(n));

//         },
//     },
//     actions: {
//         initdataAction({ commit }) {
//             commit('INIT_DATA');
//         },
//         sendmessageAction({ commit }, content) {
//             commit('SEND_MESSAGE', content);
//         },
//         selectAction({ commit }, id) {
//             commit('SELECT_SESSION', id);
//             commit('OPEN_CHAT');
//         },
//         deletesessionAction({ commit }) {
//             commit('DELETE_SESSION');
//         },
//         searchlistAction({ commit }, n) {
//             commit('SEARCH_LIST', n);
//         },
//     },
// });


// export default store;
// export const actions = {
//     initData: ({ dispatch }) => dispatch('INIT_DATA'),
//     sendMessage: ({ dispatch }, content) => dispatch('SEND_MESSAGE', content),
//     selectSession: ({ dispatch }, id) => dispatch('SELECT_SESSION', id),
//     search: ({ dispatch }, value) => dispatch('SET_FILTER_KEY', value)
// };

/**
 * Vuex
 * http://vuex.vuejs.org/zh-cn/intro.html
 */
import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';


Vue.use(Vuex);

const now = new Date();
const store = new Vuex.Store({
    state: {
        time: true,
        school: sessionStorage.school,
        // 当前用户
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

        //DOM 操作
        // sidebarshow: false,
    },
    getters: {
        // school: state => {
        //     return state.school;
        // },
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

        //DOM操作
        // sidebarshow: state => {
        //     return state.sidebarshow;
        // },
    },
    mutations: {
        INIT_DATA(state) {
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
                })
        },
        // 发送消息
        SEND_MESSAGE(state, content) {
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
        // 選擇對象
        SELECT_SESSION(state, id) {

            // if (state.time) {
            //     clearInterval(state.time);
            //     console.log('clear');
            // }

            state.currentSessionId = id;
            let current = state.list.find(({ parent_id }) => parent_id == state.currentSessionId);
            axios
                .post('getmessage', {
                    sender: state.user.name,
                    sender_id: state.user.id,
                    // user_id: current.user.user_id,
                    parent_id: current.parent_id,
                })
                .then((response) => {
                    // console.log('res', response.data.length);
                    state.sessions = [];
                    for (var i = 0; i < response.data.length; i++) {
                        state.sessions.push(
                            {
                                content: response.data[i].message,
                                date: response.data[i].created_at,
                                push: response.data[i].status,
                            }
                        );

                    }
                    // if (current.user.user_id != null) {
                    //     axios
                    //         .post('private-event', {
                    //             user_id: current.user.user_id,
                    //         })
                    // }
                    // if (current.user.parent_id != null) {
                    //     axios
                    //         .post('private-event', {
                    //             parent_id: current.user.parent_id
                    //         })
                    // }
                    // state.time = setInterval(function () {
                    //     axios
                    //         .post('getmessage', {
                    //             sender: state.user.name,
                    //             user_id: current.user.user_id,
                    //             parent_id: current.user.parent_id,
                    //         })
                    //         .then((response) => {
                    //             // console.log('res', response.data.length);
                    //             state.sessions = [];
                    //             for (var i = 0; i < response.data.length; i++) {
                    //                 state.sessions.push(
                    //                     {
                    //                         content: response.data[i].message,
                    //                         date: response.data[i].created_at,
                    //                         push: response.data[i].status,
                    //                     }
                    //                 );

                    //             }
                    //             // console.log('message', state.sessions);
                    //         })
                    //         .catch((error) => { console.log(error) });
                    // }, 3000);

                    // console.log('message', state.sessions);
                    //             window.Echo.channel('private-event.${}')
                    //    .listen('TaskAdded',function(data){
                    // 	  console.log('received a message');
                    // 	  console.log(data);
                    // 	});
                })
                .catch((error) => { console.log(error) });
        },
        OPEN_CHAT(state) {
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
                // Start socket.io listener
                Echo.channel('laravel_database_message-' + current.parent_id + '-' + state.user.id)
                    .listen('Message', (data) => {
                        if (state.user.name) {
                            state.sessions.push(
                                {
                                    content: data.message.message,
                                    date: data.message.created_at,
                                    push: data.message.status,
                                }
                            )
                        }
                    })
                // End socket.io listener
            }
        },
        // 搜索
        // SET_FILTER_KEY(state, value) {
        //     state.filterKey = value;
        // },
        DELETE_SESSION() {
            localStorage.clear();
        },
        SEARCH_LIST(state, n) {
            // if (n === null || n === '') {
            //     state.list_show = state.list;
            // }
            state.list_show = state.list.filter(({ name }) => name.includes(n));

        },

        //DOM操作
        SIDE_SHOW(state) {
            if (state.sidebarshow === false) {
                state.sidebarshow = true
            } else {
                state.sidebarshow = false
            }
        },
    },
    actions: {
        initdataAction({ commit }) {
            commit('INIT_DATA');
        },
        sendmessageAction({ commit }, content) {
            commit('SEND_MESSAGE', content);
        },
        selectAction({ commit }, id) {
            commit('SELECT_SESSION', id);
            commit('OPEN_CHAT');
        },
        deletesessionAction({ commit }) {
            commit('DELETE_SESSION');
        },
        searchlistAction({ commit }, n) {
            commit('SEARCH_LIST', n);
        },

        //DOM操作
        // sideshow({ commit }) {
        //     console.log('commit success');
        //     commit('SIDE_SHOW');
        // },
    },
});


export default store;
// export const actions = {
//     initData: ({ dispatch }) => dispatch('INIT_DATA'),
//     sendMessage: ({ dispatch }, content) => dispatch('SEND_MESSAGE', content),
//     selectSession: ({ dispatch }, id) => dispatch('SELECT_SESSION', id),
//     search: ({ dispatch }, value) => dispatch('SET_FILTER_KEY', value)
// };
