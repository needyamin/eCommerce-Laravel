<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\CartController;
   
//  http://127.0.0.1:8000/api/login?email=needyamin@gmail.com&password=needyamin@gmail.com
//  http://127.0.0.1:8000/api/register?name=needyamin&email=needyamin@gmail.com&password=needyamin@gmail.com&password_confirmation=needyamin@gmail.com
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// http://127.0.0.1:8000/api/products         
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
});


#########################  products   #########################
################################################################

// GET /products?per_page=10&page=2
Route::get('/products', [ProductController::class, 'getProducts']);

// GET /cart
Route::get('/cart', [CartController::class, 'getCart']);

// POST: /cart 
Route::post('/cart', [CartController::class, 'addToCart']);

// DELETE: /cart/{productId}
Route::delete('/cart/{productId}', [CartController::class, 'removeFromCart']);

// PATCH: /cart/{productId}
Route::patch('/cart/{productId}', [CartController::class, 'updateQuantity']);


#########################  WATCHLIST   #########################
################################################################

// POST: /watchlist
Route::post('/watchlist', [CartController::class, 'addToWatchlist']);

// GET: /watchlist
Route::get('/watchlist', [CartController::class, 'getWatchlist']);

// DELETE: /watchlist/{productId}
Route::delete('/watchlist/{productId}', [CartController::class, 'removeFromWatchlist']);

// GET: /remove-all-watchlist
Route::get('/remove-all-watchlist', [CartController::class, 'removeAllWatchlist']);



#########################  OTHERS   #########################
################################################################
// POST: /checkout
Route::post('/checkout', [CartController::class, 'checkout_data']);
Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
// Route::post('/save-session-data', [CartController::class, 'store_checkout_session']);


