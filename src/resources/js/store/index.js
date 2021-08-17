import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
// root
// import * as getters from './getters.js';
// modules
import message from './modules/message';
import dom from './modules/dom';
import rightClick from './modules/rightClick';

Vue.use(Vuex);

export default new Vuex.Store({
    // root
    // getters,
    // 將整理好的 modules 放到 vuex 中組合
    modules: {
        message,
        dom,
        // rightClick,
    },
    // 嚴格模式，禁止直接修改 state
    strict: false
});