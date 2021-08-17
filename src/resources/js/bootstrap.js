import Vue from 'vue';
import axios from 'axios';
import Buefy from 'buefy';
import moment from 'moment-timezone'
// import 'buefy/dist/buefy.css';
import 'vue-lazy-youtube-video/dist/style.css'
import LazyYoutubeVideo from 'vue-lazy-youtube-video';
import { Transfer } from 'ant-design-vue';
Vue.use(Transfer);
import 'ant-design-vue/dist/antd.css'; // or 'ant-design-vue/dist/antd.less'
import "@fortawesome/fontawesome-free/css/all.css";
import "@fortawesome/fontawesome-free/js/all.js";

import VCharts from 'v-charts'
import VeLine from 'v-charts/lib/line.common'
import VePie from 'v-charts/lib/pie.common'
import VeRing from 'v-charts/lib/ring.common'

Vue.use(VCharts)
Vue.component(VeLine.name, VeLine, VePie, VeRing)

import excel from 'vue-excel-export'

Vue.use(excel)

import VueApexCharts from 'vue-apexcharts'
Vue.component('apexchart', VueApexCharts)
import JwPagination from 'jw-vue-pagination'
Vue.component('jw-pagination', JwPagination);
// import VueAwesomeSwiper from 'vue-awesome-swiper'
import swiper, { Navigation, Pagination, Autoplay } from 'swiper'
// // import style (>= Swiper 6.x)
// import 'swiper/swiper-bundle.css'
// // import style (<= Swiper 5.x)
// import 'swiper/css/swiper.css'
// // Vue.use(VueAwesomeSwiper, /* { default options with global component } */)
swiper.use([Navigation, Pagination, Autoplay])
import introswiper from './components/swiper/Intro-swiper.vue';
Vue.component('intro-swiper', introswiper);

// require("promise.prototype.finally").shim();
moment.tz.setDefault('Asia/Taipei')
window._ = require('lodash');

window.Vue = Vue;
Vue.use(Buefy);
Vue.component(
  'LazyYoutubeVideo', LazyYoutubeVideo
)

window.axios = axios;


let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  //   console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  // window.Popper = require('popper.js').default;
  window.$ = window.jQuery = require('jquery');

  require('bootstrap');
} catch (e) { }


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

// //for Echo
// import Echo from 'laravel-echo';
// let apitoken = document.head.querySelector('meta[name="apitoken"]');

// if (apitoken) {
//   let getapitoken = apitoken.content;
//   window.io = require('socket.io-client');

//   //接続情報
//   window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: 'http://10.112.10.119:6001/',
//     auth: {
//       headers: {
//         'Authorization': 'Bearer ' + getapitoken,
//       }
//     }
//   });
// }






