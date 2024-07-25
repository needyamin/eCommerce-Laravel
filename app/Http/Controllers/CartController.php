<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    
    public function getProducts() {
        $products = DB::table('ecommerce_products')->where('is_active', 1)->get();
        return response()->json($products);
    }


    public function getCart() {
        $cart = Session::get('cart', []);
        return response()->json($cart);
    }

    public function getWatchlist() {
        $watchlist = Session::get('watchlist', []);
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

    // Check if the product exists and is active
    if (!$product) {
        return response()->json(['status' => 'error', 'message' => 'Product not found or not active'], 404);
    }

    // Get current watchlist from session
    $watchlist = Session::get('watchlist', []);

    // Check if product already exists in watchlist
    if (isset($watchlist[$productId])) {
        // Update quantity if product is already in watchlist
        $watchlist[$productId]['quantity'] += $quantity;
    } else {
        // Add new product to watchlist
        $watchlist[$productId] = [
            'id' => $productId,
            'product_name' => $product->product_name,
            'price' => $product->price,
            'quantity' => $quantity,
            'stock_quantity' => $product->stock_quantity,
        ];
    }

    // Update the watchlist in session
    Session::put('watchlist', $watchlist);

    // Return success response with updated watchlist
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
        return response()->json(['status' => 'success', 'cart' => $cart]);
    }


    public function removeFromCart($productId) {
        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }
        return response()->json(['status' => 'success', 'cart' => $cart]);
    }


    public function updateQuantity(Request $request, $productId) {
        $quantity = $request->input('quantity');
        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }
        return response()->json(['status' => 'success', 'cart' => $cart]);
    }



    public function checkout(Request $request) {
       //\Log::info('Received checkout request:', $request->all());
        // Example: Retrieve cart items and total from request
        $cartItems = $request->input('cartItems', []);
        $cartTotal = $request->input('cartTotal', 0);
        $sessionId = session()->getId();

         // Process each cart item
       foreach ($cartItems as $item) {
          $product_name = $item['product_name'];  
          $product_id = $item['id'];  
          $price = $item['price'];  
          $quantity = $item['quantity'];  
          $stock_quantity =  $item['stock_quantity'];

        //  \Log::info('Item price:', [
        //     'name' => $name,
        //     'session_id' => $sessionId,
        //     'product_id' =>  $product_id,
        //     'price' => $price,
        //     'quantity' => $quantity,
        //     'stock_quantity' => $stock_quantity,
        // ]);

        $data = [
            'session_id' => $sessionId,
            'product_id' => $product_id,
            'user_id' => Auth::id() ?? 0,
            'price' => $price,
            'quantity' => $quantity,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ecommerce_cart')->insert($data);
       }
        Session::forget('cart');  // forgot all session
        return response()->json('Checkout successful');
    }
    

    public function applyCoupon(Request $request) {
        $couponCode = $request->input('coupon_code');
        $cartItems = Session::get('cart', []);
        // Initialize applied_coupons array in session if not already present
        $appliedCoupons = Session::get('applied_coupons', []);
        // Check if the coupon code is to clear all applied coupons
        if ($couponCode === 'clearallcoupons') {
            $appliedCoupons = [];
            Session::put('applied_coupons', $appliedCoupons);
            return response()->json(['cart' => $cartItems]);
        }
    
        // Check if the coupon code is already applied
    if (isset($appliedCoupons[$couponCode])) {
        $previousDiscount = $appliedCoupons[$couponCode]['discount_percentage'];
        // Remove previous discount applied by this coupon
        foreach ($cartItems as $key => $item) {
            $cartItems[$key]['price'] = number_format($item['price'] / (1 - ($previousDiscount / 100)), 2, '.', '');
        }
        // Remove the previous coupon from applied_coupons session
        unset($appliedCoupons[$couponCode]);
    }
    
        // Apply coupon based on coupon code
        switch ($couponCode) {
            case 'todayoffer20':
                $discountPercentage = 20; // 20% discount
                break;
            case 'weekend25':
                $discountPercentage = 25; // 25% discount
                break;
            default:
                return response()->json(['error' => 'Invalid coupon code'], 400);
        }
    
        // Apply discount to cart items
        foreach ($cartItems as $key => $item) {
            $cartItems[$key]['price'] = $item['price'] * (1 - ($discountPercentage / 100));
        }
    
        // Store or update applied coupon details in session
        $appliedCoupons[$couponCode] = [
            'discount_percentage' => $discountPercentage,
        ];

        Session::put('applied_coupons', $appliedCoupons);
        Session::put('cart', $cartItems);
        return response()->json(['cart' => $cartItems]);
    }



    public function store_checkout_session(Request $request) {
         $cartItems = $request->input('cartItems', []);
         $cartTotal = $request->input('cartTotal', 0);
         $sessionId = session()->getId();
 
          // Process each cart item
        foreach ($cartItems as $item) {
           $product_name = $item['product_name'];  
           $product_id = $item['id'];  
           $price = $item['price'];  
           $quantity = $item['quantity'];  
           $stock_quantity =  $item['stock_quantity'];

         $data = [
             'session_id' => $sessionId,
             'product_id' => $product_id,
             'user_id' => Auth::id() ?? 0,
             'price' => $price,
             'quantity' => $quantity,
             'created_at' => now(),
             'updated_at' => now(),
         ];
         DB::table('ecommerce_cart')->insert($data);
        }
 
     }
     
    

    
    
}
