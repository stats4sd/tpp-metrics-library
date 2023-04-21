import axios from 'axios';
import _ from 'lodash';
import {createApp} from 'vue';

require('bootstrap');


window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');

import ExampleComponent from "./components/ExampleComponent.vue"

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp(ExampleComponent)

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: process.env.VITE_PUSHER_PROXY_PORT,
    wssPort: process.env.VITE_PUSHER_PROXY_PORT,
    disableStats: true,
    encrypted: true,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});