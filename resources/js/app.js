import "./bootstrap";
import { createApp } from "vue";
import vuetify from "./vuetify";
import router from "./router.js";
import store from "./store/index";

import App from "./App.vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faPhone,
    faPuzzlePiece,
    faFileContract,
    faRightFromBracket,
    faPlus,
    faXmark,
    faCopy,
    faEye,
    faPenAlt,
    faTrash,
} from "@fortawesome/free-solid-svg-icons";

const app = createApp({});
library.add(
    faPhone,
    faPuzzlePiece,
    faFileContract,
    faRightFromBracket,
    faPlus,
    faXmark,
    faCopy,
    faEye,
    faPenAlt,
    faTrash
);
app.component("application", App);

app.use(router)
    .use(vuetify)
    .use(store)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount("#app");

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
