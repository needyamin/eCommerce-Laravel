// Import libraries
import axios from 'axios';
import alertify from 'alertifyjs';
import $ from 'jquery';


$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Function to update the watchlist count
    function updateWatchlistCount() {
        axios.get('/api/watchlist')
            .then(response => {
                if (response.data && typeof response.data === 'object') {
                    const watchlist = Object.values(response.data);
                    const totalItems = watchlist.length; // Count the number of items in the watchlist
                    document.getElementById('total-watchlist-items').innerText = totalItems;
                } else {
                    console.error('Response data is not in the expected format:', response.data);
                }
            })
            .catch(error => {
                console.error('There was an error fetching the watchlist!', error);
            });
    }

    // Function to add an item to the watchlist
    function addToWatchlist(productId, quantity) {
        axios.post('/api/watchlist', { product_id: productId, quantity: quantity })
            .then(response => {
                if (response.data && response.data.status === 'success') {
                    alertify.success('Item added to watchlist');
                    updateWatchlistCount(); // Update the watchlist count
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

    // Function to fetch and display the watchlist
    function fetchWatchlist() {
        axios.get('/api/watchlist')
            .then(response => {
                if (response.data && typeof response.data === 'object') {
                    const watchlist = Object.values(response.data);
                    console.log('Watchlist:', watchlist);
                    updateWatchlistCount(); // Update the watchlist count
                } else {
                    console.error('Response data is not in expected format:', response.data);
                }
            })
            .catch(error => {
                console.error('There was an error fetching the watchlist!', error);
            });
    }

    // Function to update the cart count
    function updateCartCount() {
        axios.get('/api/cart')
            .then(response => {
                if (response.data && typeof response.data === 'object') {
                    const cartItems = Object.values(response.data);
                    const totalItems = cartItems.reduce((total, item) => {
                        const quantity = parseInt(item.quantity, 10);
                        if (!isNaN(quantity)) {
                            return total + quantity;
                        } else {
                            console.error('Invalid item quantity:', item);
                            return total;
                        }
                    }, 0);
                    document.getElementById('total-cart-items').innerText = totalItems;
                    document.getElementById('total-cart-items-mobile').innerText = totalItems;
                } else {
                    console.error('Response data is not in the expected format:', response.data);
                }
            })
            .catch(error => {
                console.error('There was an error fetching the cart!', error);
            });
    }

    // Function to add an item to the cart
    function addToCart(productId, quantity) {
        $.ajax({
            url: '/api/cart',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { product_id: productId, quantity: quantity },
            success: function(response) {
                console.log('Add to Cart Response:', response);
                alertify.success('Item added to cart');
                updateCartCount(); // Update the cart count after adding an item
            },
            error: function(xhr) {
                console.error('There was an error adding the product to the cart!', xhr);
                alertify.error('Failed to add item to cart');
            }
        });
    }

    // Function to remove an item from the cart
    function removeFromCart(productId) {
        console.log(`Removing from cart: productId=${productId}`);
        axios.delete(`/api/cart/${productId}`)
            .then(response => {
                if (response.data && typeof response.data === 'object') {
                    updateCartCount(); // Update the cart count after removing an item
                    alertify.success('Item removed from cart');
                } else {
                    console.error('Response data is not in expected format:', response.data);
                    alertify.error('Failed to remove item from cart');
                }
            })
            .catch(error => {
                console.error('There was an error removing the product from the cart!', error);
                alertify.error('Failed to remove item from cart');
            });
    }


    // Function to remove an item from the watch
    function removeFromWatch(productId) {
        console.log(`Removing from watchlist: productId=${productId}`);
        axios.delete(`/api/watchlist/${productId}`)
            .then(response => {
                if (response.data && typeof response.data === 'object') {
                    const watchlist = Object.values(response.data);
                    console.log('Watchlist:', watchlist);
                    updateWatchlistCount();
                    alertify.success('Item removed from watchlist');
                } else {
                    console.error('Response data is not in expected format:', response.data);
                    alertify.error('Failed to remove item from watchlist');
                }
            })
            .catch(error => {
                console.error('There was an error removing the product from the watchlist!', error);
                alertify.error('Failed to remove item from watchlist');
            });
    }

    // Load products, cart, and watchlist on page load
    fetchWatchlist();
    updateCartCount();

    // Event listener for add to cart buttons
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('id');
        const quantity = $(this).data('quantity');
        addToCart(productId, quantity);
    });

    // Event listener for add to watchlist buttons
    $(document).on('click', '.add-to-watchlist', function() {
        const productId = $(this).data('id');
        const quantity = $(this).data('quantity');
        addToWatchlist(productId, quantity);
    });

    // Event listener for remove from cart buttons
    $(document).on('click', '.remove-from-cart', function() {
        const productId = $(this).data('id');
        removeFromCart(productId);
    });

    // Event listener for remove from watchlist buttons
    $(document).on('click', '.remove-from-watch', function() {
        const productId = $(this).data('id');
        removeFromWatch(productId);
    });
});






// Import CSS
import 'alertifyjs/build/css/alertify.min.css';
import 'alertifyjs/build/css/themes/default.min.css';
