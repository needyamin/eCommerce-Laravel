
<template>

  <div>

     <div class="product-grid container">
       <div class="showcase" v-for="product in products" :key="product.id">
        <div class="showcase-banner">
          
    <router-link to="/checkout">
         <img src="template/user/assets/images/products/jacket-4.jpg" alt="{{ product.name }}" class="product-img default">
         <img src="template/user/assets/images/products/jacket-4.jpg" alt="{{ product.name }}" width="300" class="product-img hover">
    </router-link>

         <p class="showcase-badge">{{ product.discount ?? '0'}}% Dynamic</p>
         <div class="showcase-actions">
          <button class="btn-action" @click="addToWatchlist(product.id, 1)">
             <ion-icon name="heart-outline" role="img" class="md hydrated" aria-label="heart outline"></ion-icon>
           </button>
           <button class="btn-action">
             <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
           </button>
           <button class="btn-action">
             <ion-icon name="repeat-outline" role="img" class="md hydrated" aria-label="repeat outline"></ion-icon>
           </button>

            <button class="btn-action" @click="addToCartHandler(product.id, 1)">
             <ion-icon name="bag-add-outline" role="img" class="md hydrated" aria-label="bag add outline"></ion-icon>
           </button>
           
           
         </div>
       </div>
     </div>
   </div>
  </div>

 </template>
 
 <script>
 import axios from 'axios';
 import { mapActions, mapGetters } from 'vuex';
 
 export default {
   name: 'ProductGrid',
   data() {
     return {
       products: [],
       showTotalInTemplate1: true
     };
   },
   created() {
     this.getProducts();
     this.getCart();
   },
   methods: {
     ...mapActions(['addToCart', 'setCart']),
     getProducts() {
       axios.get('/api/products')
         .then(response => {
           this.products = response.data.map(product => ({
             ...product,
             quantity: 1
           }));
         })
         .catch(error => {
           console.error('There was an error fetching the products!', error);
         });
     },
     getCart() {
       axios.get('/api/cart')
         .then(response => {
           if (response.data && typeof response.data === 'object') {
             const cart = Object.values(response.data);
             this.setCart(cart);
           } else {
             console.error('Response data is not in expected format:', response.data);
           }
         })
         .catch(error => {
           console.error('There was an error fetching the cart!', error);
         });
     },

     addToCartHandler(productId, quantity) {
       axios.post('/api/cart', { product_id: productId, quantity: quantity })
         .then(response => {
           if (response.data && typeof response.data.cart === 'object') {
             const product = this.products.find(product => product.id === productId);
             if (product) {
               this.addToCart({ ...product, quantity });
               alertify.success('Item added to cart');
             }
           } else {
             console.error('Response data is not in expected format:', response.data.cart);
             alertify.error('Failed to add item to cart');
           }
         })
         .catch(error => {
           console.error('There was an error adding the product to the cart!', error);
           alertify.error('Failed to add item to cart');
         });
     },
   
   addToWatchlist(productId, quantity) {
      axios.post('/api/watchlist', { product_id: productId, quantity: quantity })
        .then(response => {
          if (response.data && response.data.status === 'success') {
            alertify.success('Item added to watchlist');
            // Optionally, update local state or perform additional actions after success
          } else {
            console.error('Failed to add item to watchlist:', response.data.message);
            alertify.error('Failed to add item to watchlist');
          }
        })
        .catch(error => {
          console.error('Error adding item to watchlist:', error);
          alertify.error('Failed to add item to watchlist');
        });
    }
  },
   computed: {
     ...mapGetters(['totalItemsInCart']),
   }
 };
 </script>