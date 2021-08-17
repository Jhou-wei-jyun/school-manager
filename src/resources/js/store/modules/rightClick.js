const types = {
    UPDATE_RIGHT_MENU_STATUS: 'rightClick/UPDATE_RIGHT_MENU_STATUS',
}

const state = {
    rightMenu: {
        status: "none",
        top: "0px",
        left: "0px",
        rightMenuList: [],
        target_id: null,
    }
}

const getters = {
    status: state => {
        return state.rightMenu.status;
    },
    top: state => {
        return state.rightMenu.top;
    },
    left: state => {
        return state.rightMenu.left;
    },
    rightMenuList: state => {
        return state.rightMenu.rightMenuList;
    },
    target_id: state => {
        return state.rightMenu.target_id;
    },
}

const actions = {
    RightClick({ commit }, menuObj) {

        console.log('click', menuObj);
        commit(types.UPDATE_RIGHT_MENU_STATUS, menuObj);
    },
}

const mutations = {
    [types.UPDATE_RIGHT_MENU_STATUS](state, menuObj) {
        state.rightMenu.status = menuObj.status;
        state.rightMenu.top = menuObj.top;
        state.rightMenu.left = menuObj.left;
        state.rightMenu.rightMenuList = menuObj.rightMenuList;
        state.rightMenu.target_id = menuObj.target_id;
    }
}
export default {
    state,
    getters,
    actions,
    mutations
}