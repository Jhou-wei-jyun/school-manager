/*
  這邊可以改為用 types 物件取代 matutions_type.js (繼續用也可以)
  然後在前面加上模組名稱作為前綴，用來避免與其他模組重複。
  因為 action、mutation、和 getter 依然是註冊在全域的命名空間
*/
const types = {
    STUDENT_SHOW: 'dom/STUDENT_SHOW',
    NAV_COVER_TOGGLE: 'dom/NAV_COVER_TOGGLE',
    TRASH_CLICK: 'dom/TRASH_CLICK',
    SIDE_BAR_SHOW: 'dom/SIDE_BAR_SHOW',
    UPDATE_NAV_WIDTH: 'dom/UPDATE_NAV_WIDTH',
}

// count state 必須是 Object
const state = {
    studentshow: true,
    navCover: false,
    trashClick: false,
    sidebarshow: false,
    navWidth: null,
}

// getters 也可以整理到這邊直接返回 count 內容
const getters = {
    studentshow: state => {
        return state.studentshow;
    },
    navCover: state => {
        return state.navCover;
    },
    trashClick: state => {
        return state.trashClick;
    },
    sidebarshow: state => {
        return state.sidebarshow;
    },
    navWidth: state => {
        return state.navWidth;
    },
}

// actions 也是以 Object 形式建構。
const actions = {
    showchange({ commit }) {
        console.log('commit success show');
        commit(types.STUDENT_SHOW);
    },
    navCoverToggle({ commit }) {
        commit(types.NAV_COVER_TOGGLE);
    },
    trashClick({ commit }) {
        commit(types.TRASH_CLICK);
    },
    sideshow({ commit }) {
        commit(types.SIDE_BAR_SHOW);
    },
    updateNavWidth({ commit }, payload) {
        commit(types.UPDATE_NAV_WIDTH, payload);
    },
}

// mutations
const mutations = {
    [types.UPDATE_NAV_WIDTH](state, width) {

        state.navWidth = width
    },
    [types.STUDENT_SHOW](state) {
        if (state.studentshow === true) {
            console.log('commit success true');
            state.studentshow = false;
            console.log(state.studentshow);
        } else {
            console.log('commit success false');
            state.studentshow = true;
            console.log(state.studentshow);
        }
    },
    [types.NAV_COVER_TOGGLE](state) {
        state.navCover = !state.navCover;
    },
    [types.TRASH_CLICK](state) {
        state.trashClick = !state.trashClick;
    },
    [types.SIDE_BAR_SHOW](state) {
        if (state.sidebarshow === false) {
            state.sidebarshow = true
        } else {
            state.sidebarshow = false
        }
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
