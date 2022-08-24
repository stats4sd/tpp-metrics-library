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
