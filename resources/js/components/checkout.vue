<template>
  <div class="container mt-5">
    <!-- Cart Display -->
    <div class="row mt-4">
      <div class="card mb-4">
        <div class="card-body">
          <div v-if="cart.length > 0">
            <h2>Cart</h2>
            <div v-for="item in cart" :key="item.id">
              <h5 class="card-title">
                {{ item.name }} - Quantity: {{ item.quantity }} -
                <p class="card-text"> Total: ${{ item.price * item.quantity }} </p>
                <button class="btn btn-primary" @click="removeFromCart(item.id)">Remove</button>
              </h5>
            </div>
            <p>Total Items in Cart: {{ totalItemsInCart }}</p>
            <p>Cart Total: ${{ cartTotal }}</p>
          </div>

      

          <!-- Checkout Button -->
          <div>
            <button class="btn btn-primary" @click="checkout">Checkout</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      cart: [],
      cartTotal: 0,
      couponCode: 0,
      totalItemsAdded: 0,
    };
  },
  created() {
    console.log('Vue instance created');
    this.getProducts();
    this.getCart();
  },
  methods: {
    getProducts() {
      console.log('Fetching products...');
      axios.get('/api/products')
        .then(response => {
          this.products = response.data.map(product => ({
            ...product,
            quantity: 1
          }));
          console.log('Products fetched:', this.products);
        })
        .catch(error => {
          console.error('There was an error fetching the products!', error);
        });
    },
    getCart() {
      console.log('Fetching cart...');
      axios.get('/api/cart')
        .then(response => {
          if (response.data && typeof response.data === 'object') {
            this.cart = Object.values(response.data);
            this.calculateCartTotal();
            console.log('Cart fetched:', this.cart);
          } else {
            console.error('Response data is not in expected format:', response.data);
          }
        })
        .catch(error => {
          console.error('There was an error fetching the cart!', error);
        });
    },
    addToCart(productId, quantity) {
      console.log(`Adding to cart: productId=${productId}, quantity=${quantity}`);
      axios.post('/api/cart', { product_id: productId, quantity: quantity })
        .then(response => {
          if (response.data && typeof response.data.cart === 'object') {
            this.cart = Object.values(response.data.cart);
            console.log('Item added to cart:', this.cart);
            this.calculateCartTotal();
            this.totalItemsAdded++;
            alertify.success('Item added to cart');
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
    removeFromCart(productId) {
      console.log(`Removing from cart: productId=${productId}`);
      axios.delete(`/api/cart/${productId}`)
        .then(response => {
          if (response.data && typeof response.data.cart === 'object') {
            this.cart = Object.values(response.data.cart);
            console.log('Item removed from cart:', this.cart);
            this.calculateCartTotal();
            alertify.success('Item removed from cart');
          } else {
            console.error('Response data is not in expected format:', response.data.cart);
            alertify.error('Failed to remove item from cart');
          }
        })
        .catch(error => {
          console.error('There was an error removing the product from the cart!', error);
          alertify.error('Failed to remove item from cart');
        });
    },
    updateQuantity(productId, quantity) {
      console.log(`Updating quantity: productId=${productId}, quantity=${quantity}`);
      
      if (quantity < 1) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
          axios.delete(`/api/cart/${productId}`)
            .then(response => {
              if (response.data && typeof response.data.cart === 'object') {
                this.cart = Object.values(response.data.cart);
                console.log('Item removed from cart:', this.cart);
                this.calculateCartTotal();
                alertify.success('Item removed from cart');
              } else {
                console.error('Response data is not in expected format:', response.data.cart);
                alertify.error('Failed to remove item from cart');
              }
            })
            .catch(error => {
              console.error('There was an error removing the item from cart!', error);
              alertify.error('Failed to remove item from cart');
            });
        } else {
          let item = this.cart.find(item => item.id === productId);
          if (item) item.quantity = 1;
        }
      } else {
        let item = this.cart.find(item => item.id === productId);
        if (item) item.quantity = quantity;

        axios.patch(`/api/cart/${productId}`, { quantity: quantity })
          .then(response => {
            if (response.data && response.data.status === 'success') {
              console.log('Quantity updated:', response.data.cart);
              this.calculateCartTotal();
              alertify.success('Quantity updated');
            } else {
              console.error('Response data is not in expected format:', response.data);
              alertify.error('Failed to update quantity');
            }
          })
          .catch(error => {
            console.error('There was an error updating the product quantity!', error);
            alertify.error('Failed to update quantity');
          });
      }
    },
    checkout() {
      console.log('Checking out...');
      
      let checkoutData = {
        cartItems: this.cart,
        cartTotal: this.cartTotal
      };

      axios.post('/api/checkout', checkoutData)
        .then(response => {
          this.cart = [];
          this.cartTotal = 0;

          console.log('Checkout successful:', response.data);
          alertify.success('Checkout successful!');
        })
        .catch(error => {
          console.error('There was an error during checkout!', error);
          alertify.error('Checkout failed');
        });
    },
    applyCoupon() {
      console.log('Applying coupon:', this.couponCode);
      
      axios.post('/api/apply-coupon', { coupon_code: this.couponCode })
        .then(response => {
          if (response.data && typeof response.data.cart === 'object') {
            this.cart = Object.values(response.data.cart);
            console.log('Cart with coupon applied:', this.cart);
            this.calculateCartTotal();
            alertify.success('Coupon applied successfully');
          } else {
            console.error('Response data is not in expected format:', response.data.cart);
            alertify.error('Failed to apply coupon');
          }
        })
        .catch(error => {
          console.error('There was an error applying the coupon:', error);
          alertify.error('Failed to apply coupon');
        });
    },
    calculateCartTotal() {
      this.cartTotal = this.cart.reduce((total, item) => {
        return total + (item.price * item.quantity);
      }, 0);
      
      this.cartTotal = parseFloat(this.cartTotal.toFixed(2));
    }
  },
  computed: {
    totalItemsInCart() {
      return this.cart.reduce((total, item) => {
        return total + item.quantity;
      }, 0);
    }
  }
};

</script>



<style scoped>
/* Your styles here */



</style>
