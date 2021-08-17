/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';
import store from './store/';
// import JsonExcel from 'vue-json-excel';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('demo-component', require('./components/DemoComponent.vue').default);
Vue.component('welcome-component', require('./components/login/WelcomeComponent.vue').default);
Vue.component('notify-component', require('./components/view/notify/NotifyComponent.vue').default);
Vue.component('bottom-component', require('./components/BottomComponent.vue').default);
Vue.component('record-component', require('./components/RecordComponent.vue').default);
Vue.component('redis-component', require('./components/RedisComponent.vue').default);
Vue.component('nav-component', require('./components/modules/NavComponent.vue').default);
Vue.component('profile-component', require('./components/modules/Profile.vue').default);
// Vue.component('profile-component', require('./components/ProfileComponent.vue').default);
Vue.component('department-component', require('./components/view/department/DepartmentComponent.vue').default);
Vue.component('department-detail-component', require('./components/view/department/DepartmentDetailComponent.vue').default);
Vue.component('device-component', require('./components/view/device/DeviceComponent.vue').default);
// Vue.component('employee-component', require('./components/EmployeeComponent.vue').default);
Vue.component('property-component', require('./components/PropertyComponent.vue').default);
Vue.component('new-employee-component', require('./components/NewEmployeeComponent.vue').default);
Vue.component('material-component', require('./components/MaterialComponent.vue').default);
Vue.component('area-component', require('./components/AreaComponent.vue').default);
// Vue.component('parents-component', require('./components/ParentsComponent.vue').default);
Vue.component('teacher-component', require('./components/view/teacher/TeacherComponent.vue').default);
// mainhome chart
Vue.component('main-home-component', require('./components/view/homepage/mainhomeComponent.vue').default);
Vue.component('chart-component', require('./components/ChartComponent.vue').default);
Vue.component('about-component', require('./components/AboutComponent.vue').default);
Vue.component('announce-component', require('./components/view/announce/AnnounceComponent.vue').default);
Vue.component('account-component', require('./components/view/teacher/AccountComponent.vue').default);
Vue.component('becon-component', require('./components/view/becon/BeconComponent.vue').default);
Vue.component('attendance-component', require('./components/view/attendance/AttendanceComponent.vue').default);
Vue.component('right-component', require('./components/view/right/RightComponent.vue').default);
Vue.component('contact-component', require('./components/view/contact/ContactComponent.vue').default);
Vue.component('album-component', require('./components/view/album/AlbumComponent.vue').default);
Vue.component('album-detail-component', require('./components/view/album/AlbumDetailComponent.vue').default);
Vue.component('album-child', require('./components/view/album/AlbumChild.vue').default);
Vue.component('async-component', require('./components/AsyncMachines.vue').default);
Vue.component('optionsetting-component', require('./components/OptionSettingComponent.vue').default);
Vue.component('medicine-component', require('./components/view/medicine/MedicineComponent.vue').default);
Vue.component('leave-component', require('./components/view/leave/LeaveComponent.vue').default);
Vue.component('question-component', require('./components/view/question/QuestionComponent.vue').default);


// Vue.component('downloadExcel', JsonExcel);

// Vue.component('home-message-component', require('./components/HomeMessageComponent.vue').default);
// Vue.component('home-kpi-component', require('./components/HomeKpiComponent.vue').default);
// Vue.component('view-arrive-chart-component', require('./components/ViewArriveChartComponent.vue').default);
// Vue.component('home-overview-chart-component', require('./components/HomeOverviewChartComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    store,
}).$mount('#app');
const nav = new Vue({
    store,
}).$mount('#nav');
const top = new Vue({
    store,
}).$mount('#top');
const bottom = new Vue({
    el: '#bottom',
});
