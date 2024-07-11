// app.js

import './bootstrap';
import '../css/app.css'; 
import { createApp } from 'vue';
import router from'./routes.js'
import store from './store'; 

import layout from './layout.vue';

const app = createApp({});
app.component('template-layout', layout);
app.use(router).use(store).mount('#apps');
