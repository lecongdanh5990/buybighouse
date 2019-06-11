import VueRouter from 'vue-router';

import { Form, HasError, AlertError } from "vform";
import Chart from 'chart.js';
import Menu from './components/public/Menu.vue';
import Info from './components/public/Info.vue';
import Animation from './components/public/Animation.vue';
import PurposeDetail from './components/public/PurposeDetail.vue';

import swal from 'sweetalert2';
window.swal=swal;
const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
window.toast = toast;


// CommonJS

require('./bootstrap');
// require("bootstrap/js/src/dropdown");
window.Vue = require('vue');
Vue.use(VueRouter);
//window.Swal=Swal;

const options = {
  color: "#bffaf3",
  failedColor: "#874b4b",
  thickness: "5px",
  transition: {
    speed: "1s",
    opacity: "0.6s",
    termination: 300
  },
  autoRevert: true,
  location: "left",
  inverse: false
};

const routes = [
  { path: "/dashboard", component: require("./components/public/Dashboard.vue").default },
  { path: "/tomorrow", component: require("./components/public/Tomorrow.vue").default },
  { path: "/in-week", component: require("./components/public/InWeek.vue").default },
  { path: "/in-month", component: require("./components/public/InMonth.vue").default },
  { path: "/target", component: require("./components/public/Target.vue").default },
  { path: "/purpose", component: require("./components/public/Purpose.vue").default },
  { path: "/purpose/detail/:id",component: PurposeDetail},
  { path: "/purpose/add", component: require("./components/public/PurposeAdd.vue").default },
  { path: "/status", component: require("./components/public/Status.vue").default },
  { path: "/time-analytic",component: require("./components/public/TimeAnalytic.vue").default},
  { path: "/user/profile",component: require("./components/public/UserProfile.vue").default},


  { path: "/admin/user", component: require("./components/admin/User.vue").default }
];

const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
})
window.Form=Form;
window.Event=new Vue();



Vue.component("menu-component", Menu);
Vue.component("info-component", Info);
Vue.component("animation-component", Animation);

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

const app = new Vue({
    el: '#app',
    router,
});
