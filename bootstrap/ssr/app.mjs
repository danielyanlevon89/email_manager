import axios from "axios";
import { createApp, defineAsyncComponent } from "vue/dist/vue.esm-bundler.js";
import { renderSpladeApp, SpladePlugin } from "@protonemedia/laravel-splade";
import ToastPlugin from "vue-toast-notification";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.quitIntervals = function(limit) {
  limit = limit || 1e3;
  var np, n = setInterval(function() {
  }, 1e5);
  np = Math.max(0, n - limit);
  while (n > np) {
    clearInterval(n--);
  }
};
const app = "";
const style = "";
const jodit = "";
const themeBootstrap = "";
const el = document.getElementById("app");
createApp({
  render: renderSpladeApp({ el })
}).use(SpladePlugin, {
  "max_keep_alive": 10,
  "transform_anchors": false,
  "progress_bar": true
}).use(ToastPlugin, {
  position: "top-right",
  duration: 2e3
}).component("sidebar", defineAsyncComponent(() => import("./assets/Sidebar.5443714f.mjs"))).component("checkimapconnection", defineAsyncComponent(() => import("./assets/CheckImapConnection.7ac7d771.mjs"))).component("checksmtpconnection", defineAsyncComponent(() => import("./assets/CheckSmtpConnection.b59e0d4c.mjs"))).component("sendemail", defineAsyncComponent(() => import("./assets/SendEmail.08defad9.mjs"))).component("Counter", defineAsyncComponent(() => import("./assets/Counter.e94eaf83.mjs"))).mount(el);
