import "./bootstrap";
import "./main";
import "../css/app.css";
import "@protonemedia/laravel-splade/dist/style.css";
import "@protonemedia/laravel-splade/dist/jodit.css";

import {createApp, defineAsyncComponent} from "vue/dist/vue.esm-bundler.js";
import {renderSpladeApp, SpladePlugin} from "@protonemedia/laravel-splade";


import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';


const el = document.getElementById("app");

createApp({
    render: renderSpladeApp({el})
})
    .use(SpladePlugin, {
        "max_keep_alive": 10,
        "transform_anchors": false,
        "progress_bar": true
    })
    .use(ToastPlugin, {
        position: 'top-right',
        duration: 2000
    })
    .component('sidebar', defineAsyncComponent(() => import("./components/Sidebar.vue")))
    .component('checkimapconnection', defineAsyncComponent(() => import("./components/CheckImapConnection.vue")))
    .component('checksmtpconnection', defineAsyncComponent(() => import("./components/CheckSmtpConnection.vue")))
    .component('sendemail', defineAsyncComponent(() => import("./components/SendEmail.vue")))
    .component('import', defineAsyncComponent(() => import("./components/Import.vue")))

    .component('Counter', defineAsyncComponent(() => import("./components/Counter.vue")))
    .mount(el);

