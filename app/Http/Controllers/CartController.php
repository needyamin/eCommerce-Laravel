<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    // Cache Key for Cart
    protected function getCartCacheKey() {
        return 'cart_' . session()->getId();
    }

    public function getCart() {
        $cart = Cache::remember($this->getCartCacheKey(), 60, function() {
            return Session::get('cart', []);
        });

        return response()->json($cart);
    }

    public function getWatchlist() {
        $watchlist = Cache::remember('watchlist_' . session()->getId(), 60, function() {
            return Session::get('watchlist', []);
        });

        return response()->json($watchlist);
    }

    public function addToWatchlist(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Fetch the product from database
        $product = DB::table('ecommerce_products')
                    ->where('id', $productId)
                    ->where('is_active', 1)
                    ->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found or not active'], 404);
        }

        $watchlist = Session::get('watchlist', []);
        if (isset($watchlist[$productId])) {
            $watchlist[$productId]['quantity'] += $quantity;
        } else {
            $watchlist[$productId] = [
                'id' => $productId,
                'product_name' => $product->product_name,
                'price' => $product->price,
                'quantity' => $quantity,
                'stock_quantity' => $product->stock_quantity,
            ];
        }

        Session::put('watchlist', $watchlist);

        // Cache updated watchlist
        Cache::put('watchlist_' . session()->getId(), $watchlist, 60);

        return response()->json(['status' => 'success', 'watchlist' => $watchlist]);
    }

    public function addToCart(Request $request) {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = DB::table('ecommerce_products')->where('id', $productId)->where('is_active', 1)->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found or not active'], 404);
        }

        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'product_name' => $product->product_name,
                'price' => $product->price,
                'quantity' => $quantity,
                'stock_quantity' => $product->stock_quantity,
            ];
        }

        Session::put('cart', $cart);

        // Cache updated cart
        Cache::put($this->getCartCacheKey(), $cart, 60);

        return response()->json(['status' => 'success', 'cart' => $cart]);
    }

    public function removeFromCart($productId) {
        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);

            // Cache updated cart
            Cache::put($this->getCartCacheKey(), $cart, 60);
        }

        return response()->json(['status' => 'success', 'cart' => $cart]);
    }

    public function removeFromWatchlist($productId) {
        $watchlist = Session::get('watchlist', []);
        if (isset($watchlist[$productId])) {
            unset($watchlist[$productId]);
            Session::put('watchlist', $watchlist);

            // Cache updated watchlist
            Cache::put('watchlist_' . session()->getId(), $watchlist, 60);
        }

        return response()->json(['status' => 'success', 'watchlist' => $watchlist]);
    }

    public function removeAllWatchlist(Request $request) {
        Session::forget('watchlist');
        Cache::forget('watchlist_' . session()->getId());

        return response()->json(['status' => 'success']);
    }

    public function updateQuantity(Request $request, $productId) {
        $quantity = $request->input('quantity');
        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);

            // Cache updated cart
            Cache::put($this->getCartCacheKey(), $cart, 60);
        }

        return response()->json(['status' => 'success', 'cart' => $cart]);
    }

    public function checkout_data(Request $request) {
        $cartItems = $request->input('cartItems', []);
        $sessionId = session()->getId();

        foreach ($cartItems as $item) {
            $data = [
                'session_id' => $sessionId,
                'product_id' => $item['id'],
                'user_id' => Auth::id() ?? 0,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('ecommerce_cart')->insert($data);
        }

        Session::forget('cart');
        Cache::forget($this->getCartCacheKey());

        return response()->json('Checkout successful');
    }

    public function applyCoupon(Request $request) {
        $couponCode = $request->input('coupon_code');
        $cartItems = Session::get('cart', []);
        $appliedCoupons = Session::get('applied_coupons', []);

        if ($couponCode === 'clearallcoupons') {
            $appliedCoupons = [];
            Session::put('applied_coupons', $appliedCoupons);
            Cache::put($this->getCartCacheKey(), $cartItems, 60);
            return response()->json(['cart' => $cartItems]);
        }

        if (isset($appliedCoupons[$couponCode])) {
            $previousDiscount = $appliedCoupons[$couponCode]['discount_percentage'];
            foreach ($cartItems as $key => $item) {
                $cartItems[$key]['price'] = number_format($item['price'] / (1 - ($previousDiscount / 100)), 2, '.', '');
            }
            unset($appliedCoupons[$couponCode]);
        }

        switch ($couponCode) {
            case 'todayoffer20':
                $discountPercentage = 20;
                break;
            case 'weekend25':
                $discountPercentage = 25;
                break;
            default:
                return response()->json(['error' => 'Invalid coupon code'], 400);
        }

        foreach ($cartItems as $key => $item) {
            $cartItems[$key]['price'] = $item['price'] * (1 - ($discountPercentage / 100));
        }

        $appliedCoupons[$couponCode] = ['discount_percentage' => $discountPercentage];
        Session::put('applied_coupons', $appliedCoupons);
        Session::put('cart', $cartItems);

        // Cache updated cart
        Cache::put($this->getCartCacheKey(), $cartItems, 60);

        return response()->json(['cart' => $cartItems]);
    }

    public function store_checkout_session(Request $request) {
        $cartItems = $request->input('cartItems', []);
        $sessionId = session()->getId();

        foreach ($cartItems as $item) {
            $data = [
                'session_id' => $sessionId,
                'product_id' => $item['id'],
                'user_id' => Auth::id() ?? 0,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('ecommerce_cart')->insert($data);
        }
    }
}
