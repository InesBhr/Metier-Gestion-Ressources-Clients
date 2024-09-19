import "../scss/app.scss";
import Router from "./router";
import { createApp } from "vue";
import VueStripeMenu from "vue-stripe-menu";
import HeaderMenu from "./views/HeaderMenu";

window.$boosted = require("boosted");

createApp(HeaderMenu).use(Router).use(VueStripeMenu).mount("#header_menu");
createApp().use(Router).mount("#app");

export { createApp, Router };
