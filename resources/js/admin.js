import VueRouter from "vue-router";

import { Form, HasError, AlertError } from "vform";
import Chart from "chart.js";
import Menu from "./components/public/Menu.vue";
import Info from "./components/public/Info.vue";
import Animation from "./components/public/Animation.vue";
import PurposeDetail from "./components/public/PurposeDetail.vue";
//import Swal from "sweetalert2";

// CommonJS

require("./bootstrap");
// require("bootstrap/js/src/dropdown");
window.Vue = require("vue");
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
    {
        path: "/home",
        component: require("./components/public/Dashboard.vue").default
    },
    {
        path: "/today",
        component: require("./components/public/Today.vue").default
    },
    {
        path: "/purpose",
        component: require("./components/public/Purpose.vue").default
    },
    { path: "/purpose/detail/:id", component: PurposeDetail },
    {
        path: "/purpose/add",
        component: require("./components/public/PurposeAdd.vue").default
    },
    {
        path: "/status",
        component: require("./components/public/Status.vue").default
    },
    {
        path: "/time-analytic",
        component: require("./components/public/TimeAnalytic.vue").default
    },
    {
        path: "/user/profile",
        component: require("./components/public/UserProfile.vue").default
    },

    {
        path: "/admin/user",
        component: require("./components/admin/User.vue").default
    }
];

const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});
window.Form = Form;
window.Event = new Vue();

Vue.component("menu-component", Menu);
Vue.component("info-component", Info);
Vue.component("animation-component", Animation);

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

const admin = new Vue({
    el: "#admin",
    router
});
