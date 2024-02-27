import "./bootstrap";
import { createApp } from "vue";
import vuetify from "./vuetify";
import router from "./router";
import App from "./layouts/App.vue";

const app = createApp({});

app.component("application", App);

app.use(router).use(vuetify).mount("#app");
