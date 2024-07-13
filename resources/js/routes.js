// routes.js

import { createWebHistory, createRouter } from 'vue-router'
import Checkout  from './components/checkout.vue'
import ProductPage from './components/Product_Page.vue'


const routes = [
    { path: '/checkout', component: Checkout },
    { path: '/product/:id', component: ProductPage },
  ]
  

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;