/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

import Vue from "vue";
import Vuetify from "vuetify";

import "vuetify/dist/vuetify.min.css";
// import '@fortawesome/fontawesome-free/css/all.min.css';
import App from "./components/ide/App.vue";

Vue.config.productionTip = false;
Vue.use(Vuetify, {
  iconfont: "fa",
  theme: {
    primary: "#0096a9",
    secondary: "#d4e437",
    accent: "#ed7a3f",
    consoleToolbar: "#003c4c"
  }
});
Vue.component(App);
new Vue({
  el: "#app",
  components: { App }
});

