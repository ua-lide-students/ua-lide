import Vue from "vue";
import Vuetify from "vuetify";

import "vuetify/dist/vuetify.min.css";
// import '@fortawesome/fontawesome-free/css/all.min.css';
import App from "./components/ide/App.vue";

require("../css/app.scss");

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
