import "./bootstrap";
import { createApp } from "vue";
import vuetify from "./vuetify";
import router from "./router";
import store from "./store/index";

import App from "./App.vue";

const app = createApp({});

app.component("application", App);

app.use(router).use(vuetify).use(store).mount("#app");

import Swal from "sweetalert2";
window.swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});
window.toast = Toast;
