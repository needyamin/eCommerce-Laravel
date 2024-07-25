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

## APi CartController CRUD
// GET: /products : Get All Products
Route::get('/products', [CartController::class, 'getProducts']);

// GET: /cart : Get All cart
Route::get('/cart', [CartController::class, 'getCart']);

// POST: /cart : Add To Cart
Route::post('/cart', [CartController::class, 'addToCart']);

Route::delete('/cart/{productId}', [CartController::class, 'removeFromCart']);

Route::patch('/cart/{productId}', [CartController::class, 'updateQuantity']);

Route::post('/checkout', [CartController::class, 'checkout']);

Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);


// Route::post('/save-session-data', [CartController::class, 'store_checkout_session']);
Route::post('/watchlist', [CartController::class, 'addToWatchlist']);
Route::get('/watchlist', [CartController::class, 'getWatchlist']);
Route::delete('/watchlist/{productId}', [CartController::class, 'removeFromWatchlist']);
Route::get('/remove-all-watchlist', [CartController::class, 'removeAllWatchlist']);


