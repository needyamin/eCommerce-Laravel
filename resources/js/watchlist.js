// Import libraries
import axios from 'axios';
import alertify from 'alertifyjs';
import $ from 'jquery';


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