// routes.js

import { createWebHistory, createRouter } from 'vue-router'
import checkout from './components/checkout.vue'


const routes = [
    { path: '/checkout', component: checkout },
  ]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;