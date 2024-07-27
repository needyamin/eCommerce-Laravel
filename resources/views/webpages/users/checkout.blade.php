@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')

@endsection




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



@vite('resources/js/checkout.js')
<br>
@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection
