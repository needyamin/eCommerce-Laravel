@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection



<div class="product-grid container">
  @foreach ($products as $product)
    <div class="showcase">
      <div class="showcase-banner">
        <a href="{{ url('/product/' . $product->id) }}">
          <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" class="product-img default">
          <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" width="300" class="product-img hover">
        </a>
        <p class="showcase-badge">{{ $product->discount ?? '0' }}% Dynamic</p>
        <div class="showcase-actions">
          <button class="btn-action add-to-watchlist" data-id="{{ $product->id }}" data-quantity="1">
            <ion-icon name="heart-outline" role="img" class="md hydrated" aria-label="heart outline"></ion-icon>
          </button>
          <button class="btn-action">
            <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
          </button>
          <button class="btn-action">
            <ion-icon name="repeat-outline" role="img" class="md hydrated" aria-label="repeat outline"></ion-icon>
          </button>
          <button class="btn-action add-to-cart" data-id="{{ $product->id }}" data-quantity="1">
            <ion-icon name="bag-add-outline" role="img" class="md hydrated" aria-label="bag add outline"></ion-icon>
          </button>
          <button class="btn-action remove-from-cart" data-id="{{ $product->id }}">
            <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="trash outline"></ion-icon>
          </button>
        </div>
      </div>
    </div>
  @endforeach
</div>


{{ $products->links('pagination::bootstrap-5') }}










<script>
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
    });
</script>











@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection