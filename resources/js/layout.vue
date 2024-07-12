<template>
  <div class="app-container">
    <AppHeader />
    <router-view />
    <ProductGrid />
    <AppFooter />
  </div>
</template>

<script>
import AppHeader from './re_usable_users/AppHeader.vue';
import AppFooter from './re_usable_users/AppFooter.vue';
import ProductGrid from './components/ProductGrid.vue';

export default {
  name: 'Layout',
  components: {
    AppHeader,
    AppFooter,
    ProductGrid
  },
  mounted() {
    // Dynamically loading script.js
    const script = document.createElement('script');
    script.src = 'template/user/assets/js/script.js';
    script.async = true;
    document.body.appendChild(script);
  }
}
</script>

<style>
/* Import stylesheet files directly here */
@import url('https://fonts.bunny.net/css?family=Nunito');
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css');
@import '../../public/template/user/assets/css/style-prefix.css';
</style>

<script setup>
// Import JavaScript files directly here
import 'https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js';
import 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js';
import 'https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js';
import 'https://unpkg.com/ionicons@5.5.2/dist/ionicons.js';
</script>
