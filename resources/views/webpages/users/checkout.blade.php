@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')
<!-- Add Vue CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.7.16/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- alertfy -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
@endsection

<div id="app" class="container mt-5">
        <h1 align="center">Shopping Cart</h1>
        
        <h2>Products</h2>
        <div class="row">
            <div class="col-md-4" v-for="product in products" :key="product.id">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">@{{ product.name }}</h5>
                        <p class="card-text">Price: $@{{ product.price }}</p>
                        <input type="hidden" v-model.number="product.quantity" min="1">
                        <button class="btn btn-primary" @click="addToCart(product.id, product.quantity)">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <h2>Cart</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in cart" :key="item.id">
                        <td>@{{ item.name }}</td>
                        <td>$@{{ parseFloat(item.price).toFixed(2) }}</td>
                        <td>
                            <input type="number" v-model.number="item.quantity" @change="updateQuantity(item.id, item.quantity)" min="1">
                        </td>
                        <td>$@{{ parseFloat(item.price * item.quantity).toFixed(2) }}</td>
                        <td>
                            <button class="btn btn-danger" @click="removeFromCart(item.id)">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Total: $@{{ cartTotal }} bdt</h3>

    <!-- Coupon Code Input -->
    <div class="input-group mb-3">
        <input type="text" class="form-control" v-model="couponCode" placeholder="Enter coupon code" style="width:60%; flex:1">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" @click="applyCoupon">Apply Coupon</button>
        </div>
    </div>


     <button class="btn btn-success" @click="checkout">Checkout</button>
    </div>

<script>
    
new Vue({
    el: '#app',
    data: {
        products: [],
        cart: [],
        cartTotal: 0,
        couponCode:0,
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
                        quantity: 1 // Initialize quantity for each product
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
                        // Convert response object to an array
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

        // add to cart
        addToCart(productId, quantity) {
            console.log(`Adding to cart: productId=${productId}, quantity=${quantity}`);
            axios.post('/api/cart', { product_id: productId, quantity: quantity })
                .then(response => {
                    if (response.data && typeof response.data.cart === 'object') {
                        // Convert response object to an array
                        this.cart = Object.values(response.data.cart);
                        console.log('Item added to cart:', this.cart);
                        this.calculateCartTotal();
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

        // remove FormCart
        removeFromCart(productId) {
            console.log(`Removing from cart: productId=${productId}`);
            axios.delete(`/api/cart/${productId}`)
                .then(response => {
                    if (response.data && typeof response.data.cart === 'object') {
                        // Convert response object to an array
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
            
            // Check if quantity is less than 1
            if (quantity < 1) {
                // Prompt user for confirmation to remove item from cart
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    // Perform axios delete request to remove item from cart
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
                    // Reset quantity to 1 if user cancels
                    let item = this.cart.find(item => item.id === productId);
                    if (item) item.quantity = 1;
                }
            } else {
                // Find the item in the cart and update the quantity locally
                let item = this.cart.find(item => item.id === productId);
                if (item) item.quantity = quantity;

                // Perform axios patch request to update quantity on server
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
    
    // Prepare data to send in the checkout request
    let checkoutData = {

        cartItems: this.cart,    // Assuming this.cart contains items in the cart
        cartTotal: this.cartTotal  // Assuming this.cartTotal is the total amount
    };

    axios.post('/api/checkout', checkoutData)
        .then(response => {
            // Clear cart and reset cart total after successful checkout
            this.cart = [];
            this.cartTotal = 0;

            console.log('Checkout successful:', response.data); // Log the response data if needed
            alertify.success('Checkout successful!');
        })
        .catch(error => {
            console.error('There was an error during checkout!', error);
            alertify.error('Checkout failed');
        });
},


        applyCoupon() {
            console.log('Applying coupon:', this.couponCode);
            // Replace with your API call to apply coupon
            // Example: Assume the API returns updated cart with coupon applied
            axios.post('/api/apply-coupon', { coupon_code: this.couponCode })
                .then(response => {
                    if (response.data && typeof response.data.cart === 'object') {
                        // Convert response object to an array
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
            
            // Format the cart total to two decimal places
            this.cartTotal = parseFloat(this.cartTotal.toFixed(2));
        }
    
    
    }});
</script>


<br>

@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection