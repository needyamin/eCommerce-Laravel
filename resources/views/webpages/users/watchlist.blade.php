@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')
@vite('resources/js/app.js')
@vite('resources/js/watchlist.js')
@endsection


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


<br>
@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection
