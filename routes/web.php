<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\HomeController;

# Use the shortcut Ctrl + K followed by Ctrl + F (Windows/Linux)


// Route::get('/{any}', function () {
//     return view('webpages.users.home'); 
// })->where('any', '.*');

#export NODE_OPTIONS=--max-old-space-size=8192 
##### php artisan serve --host 192.168.68.196 --port 8000
#### php artisan migrate:refresh --path=/database/migrations/2024_03_16_102807_create_leave_application.php

// NPM //
## npm install vuex --save
## npm install jquery bootstrap
##

Auth::routes();

// eCommerce Homepage
Route::get('/', [HomeController::class, 'homepage'])->name('homepage');

// eCommerce Shopping Cart Page
Route::get('/cart', function () { return view('webpages.users.checkout');});

Route::get('/watchlist', function () { return view('webpages.users.watchlist');});


// eCommerce Product Page
Route::get('/product/{id}', function () { return'000';});



Route::group(['middleware' => ['auth', 'role:admin']], function () {
    // Route::get('/admin', function () {return redirect('/login');});
});

Route::group(['middleware' => ['auth', 'checkPermission:create']], function () {
    // Route::get('/admin', function () {return redirect('/login');});
});


