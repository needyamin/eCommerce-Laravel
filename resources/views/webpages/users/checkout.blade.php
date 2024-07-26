@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')
<!-- Add jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- alertify -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
@endsection

@vite('resources/js/app.js')



<div id="app" class="container mt-5">
    <h1 align="center">Shopping Cart</h1>

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
            <tbody id="cart-items">
                <!-- Cart items will be injected here -->
            </tbody>
        </table>
    </div>

    <h3>Total: $<span id="cart-total">0.00</span> bdt</h3>

    <!-- Coupon Code Input -->
    <!-- <div class="input-group mb-3">
        <input type="text" class="form-control" id="coupon-code" placeholder="Enter coupon code" style="width:60%; flex:1">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="apply-coupon">Apply Coupon</button>
        </div>
    </div> -->

    <button class="btn btn-success" id="checkout">Checkout</button>
</div>

<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Function to fetch and render the cart items
        function getCart() {
            console.log('Fetching cart...');
            $.ajax({
                url: '/api/cart',
                method: 'GET',
                success: function(response) {
                    if (response && typeof response === 'object') {
                        const cart = Object.values(response);
                        renderCartItems(cart);
                        calculateCartTotal(cart);
                        console.log('Cart fetched:', cart);
                    } else {
                        console.error('Response data is not in expected format:', response);
                    }
                },
                error: function(xhr) {
                    console.error('There was an error fetching the cart!', xhr);
                }
            });
        }

        // Function to fetch and render the watchlist
        function fetchWatchlist() {
            console.log('Fetching watchlist...');
            $.ajax({
                url: '/api/watchlist',
                method: 'GET',
                success: function(response) {
                    if (response && typeof response === 'object') {
                        const watchlist = Object.values(response);
                        console.log('Watchlist:', watchlist);
                        const totalItems = watchlist.length; 
                        document.getElementById('total-watchlist-items').innerText = totalItems;
                        // Update watchlist UI if needed
                    } else {
                        console.error('Response data is not in expected format:', response);
                    }
                },
                error: function(xhr) {
                    console.error('There was an error fetching the watchlist!', xhr);
                }
            });
        }

        // Function to update the cart count
        function updateCartCount() {
            console.log('Updating cart count...');
            $.ajax({
                url: '/api/cart',
                method: 'GET',
                success: function(response) {
                    if (response && typeof response === 'object') {
                        const cartItems = Object.values(response);
                        const totalItems = cartItems.reduce((total, item) => total + parseInt(item.quantity, 10), 0);
                        $('#total-cart-items').text(totalItems);
                    } else {
                        console.error('Response data is not in expected format:', response);
                    }
                },
                error: function(xhr) {
                    console.error('There was an error fetching the cart count!', xhr);
                }
            });
        }

        function renderCartItems(cart) {
            const cartItemsContainer = $('#cart-items');
            cartItemsContainer.empty();
            cart.forEach(item => {
                const row = `
                    <tr data-id="${item.id}">
                        <td>${item.product_name}</td>
                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                        <td style="width:150px;">
                            <input type="number" class="item-quantity" value="${item.quantity}" min="1">
                        </td>
                        <td style="width:150px;">$${(item.price * item.quantity).toFixed(2)}</td>
                        <td>
                            <button class="btn-sm btn btn-danger remove-from-cart">Remove</button>
                        </td>
                    </tr>
                `;
                cartItemsContainer.append(row);
            });
        }

        function calculateCartTotal(cart) {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            $('#cart-total').text(total.toFixed(2));
        }

        function applyCoupon() {
            const couponCode = $('#coupon-code').val();
            console.log('Applying coupon:', couponCode);
            $.ajax({
                url: '/api/apply-coupon',
                method: 'POST',
                data: { coupon_code: couponCode },
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    if (response && typeof response.cart === 'object') {
                        const cart = Object.values(response.cart);
                        renderCartItems(cart);
                        calculateCartTotal(cart);
                        alertify.success('Coupon applied successfully');
                    } else {
                        console.error('Response data is not in expected format:', response.cart);
                        alertify.error('Failed to apply coupon');
                    }
                },
                error: function(xhr) {
                    console.error('There was an error applying the coupon:', xhr);
                    alertify.error('Failed to apply coupon');
                }
            });
        }

    function checkout() {
    console.log('Checking out...');
    const cart = [];
    
    $('#cart-items tr').each(function() {
        const id = $(this).data('id');
        const quantity = parseInt($(this).find('.item-quantity').val()) || 0;
        const price = parseFloat($(this).find('.item-price').val()) || 0.0;
        const product_name = $(this).find('.item-product_name').val() || '';
        const stock_quantity = parseInt($(this).find('.item-stock_quantity').val()) || 0;
        
        cart.push({ id, quantity, product_name, price, stock_quantity });
    });

    const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Assuming CSRF token is in a meta tag

    $.ajax({
        url: '/api/checkout',
        method: 'POST',
        data: { cartItems: cart, cartTotal: parseFloat($('#cart-total').text()) || 0.0 },
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            console.log(response);
            $('#cart-items').empty();
            $('#cart-total').text('0.00');
            alertify.success('Checkout successful!');
        },
        error: function(xhr) {
            console.error('There was an error during checkout!', xhr);
            alertify.error('Checkout failed');
        }
    });
}

        // Event listeners
        $('#apply-coupon').click(applyCoupon);
        $('#checkout').click(checkout);

        $('#cart-items').on('change', '.item-quantity', function() {
            const row = $(this).closest('tr');
            const productId = row.data('id');
            const quantity = $(this).val();

            if (quantity < 1) {
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    $.ajax({
                        url: `/api/cart/${productId}`,
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(response) {
                            if (response && typeof response.cart === 'object') {
                                const cart = Object.values(response.cart);
                                renderCartItems(cart);
                                calculateCartTotal(cart);
                                alertify.success('Item removed from cart');
                            } else {
                                console.error('Response data is not in expected format:', response.cart);
                                alertify.error('Failed to remove item from cart');
                            }
                        },
                        error: function(xhr) {
                            console.error('There was an error removing the item from the cart!', xhr);
                            alertify.error('Failed to remove item from cart');
                        }
                    });
                } else {
                    $(this).val(1);
                }
            } else {
                $.ajax({
                    url: `/api/cart/${productId}`,
                    method: 'PATCH',
                    data: { quantity: quantity },
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function(response) {
                        if (response && response.status === 'success') {
                            const cart = Object.values(response.cart);
                            renderCartItems(cart);
                            calculateCartTotal(cart);
                            alertify.success('Quantity updated');
                        } else {
                            console.error('Response data is not in expected format:', response);
                            alertify.error('Failed to update quantity');
                        }
                    },
                    error: function(xhr) {
                        console.error('There was an error updating the product quantity!', xhr);
                        alertify.error('Failed to update quantity');
                    }
                });
            }
        });

        $('#cart-items').on('click', '.remove-from-cart', function() {
            const row = $(this).closest('tr');
            const productId = row.data('id');

            $.ajax({
                url: `/api/cart/${productId}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    if (response && typeof response.cart === 'object') {
                        const cart = Object.values(response.cart);
                        renderCartItems(cart);
                        calculateCartTotal(cart);
                        alertify.success('Item removed from cart');
                    } else {
                        console.error('Response data is not in expected format:', response.cart);
                        alertify.error('Failed to remove item from cart');
                    }
                },
                error: function(xhr) {
                    console.error('There was an error removing the product from the cart!', xhr);
                    alertify.error('Failed to remove item from cart');
                }
            });
        });

        // Load products, cart, and watchlist on page load
        getCart();
        fetchWatchlist();
        updateCartCount();
    });
</script>

<br>

@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection
