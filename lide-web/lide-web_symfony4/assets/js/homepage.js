import Vue from "vue";
import Vuetify from "vuetify";

import "vuetify/dist/vuetify.min.css";

import Home from "./components/homepage/Home";

Vue.use(Vuetify, {
  iconfont: "fa",
  theme: {
    primary: "#0096a9",
    secondary: "#d4e437",
    accent: "#ed7a3f",
    consoleToolbar: "#003c4c"
  }
});

Vue.component(Home);

new Vue({
  el: "#app",
  components: { Home }
});
