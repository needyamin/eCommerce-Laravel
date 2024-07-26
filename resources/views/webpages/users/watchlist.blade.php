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
    <h1 align="center">Watchlist</h1>

    <h2>Watchlist</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- Cart items will be injected here -->
            </tbody>
        </table>
    </div>




    <button class="btn btn-success" id="checkout">Shopping Home</button>
</div>

<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');


        // Function to fetch and render the watchlist
        function fetchWatchlist() {
            console.log('Fetching watchlist...');
            $.ajax({
                url: '/api/watchlist',
                method: 'GET',
                success: function(response) {
                    if (response && typeof response === 'object') {
                        const watchlist = Object.values(response);
                        renderwatchlistItemss(watchlist);
                        updateWatchlistCount();
                        // console.log('Watchlist:', watchlist);
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

        
// Function to update the watchlist count
function updateWatchlistCount() {
    $.ajax({
        url: '/api/watchlist',
        method: 'GET',
        success: function(response) {
            if (response && typeof response === 'object') {
                const watchlistItems = Object.values(response);
                const totalItems = watchlistItems.length;
                $('#total-watchlist-items').text(totalItems);
                // Update watchlist count in other parts of the UI if needed
            } else {
                console.error('Response data is not in the expected format:', response);
            }
        },
        error: function(xhr) {
            console.error('There was an error fetching the watchlist!', xhr);
        }
    });
}



   function renderwatchlistItemss(watchlist) {
            const cartItemsContainer = $('#cart-items');
            cartItemsContainer.empty();
            watchlist.forEach(item => {
                const row = `
                    <tr data-id="${item.id}">
                        <td>${item.product_name}</td>
                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                        <td>
                            <button class="btn-sm btn btn-danger remove-from-cart">Remove</button>
                        </td>
                    </tr>
                `;
                cartItemsContainer.append(row);
            });
        }


        
       
       

        $('#cart-items').on('click', '.remove-from-cart', function() {
            const row = $(this).closest('tr');
            const productId = row.data('id');

            $.ajax({
                url: `/api/watchlist/${productId}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    if (response && typeof response.watchlist === 'object') {
                        const watchlist = Object.values(response.watchlist);
                        renderwatchlistItemss(watchlist);
                        updateWatchlistCount();
                        alertify.success('Item removed from watchlist');
                    } else {
                        console.error('Response data is not in expected format:', response.watchlist);
                        alertify.error('Failed to remove item from cart');
                    }
                },
                error: function(xhr) {
                    console.error('There was an error removing the product from the cart!', xhr);
                    alertify.error('Failed to remove item from cart');
                }
            });
        });

        fetchWatchlist();
        updateWatchlistCount();
        


    });
</script>

<br>

@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection
