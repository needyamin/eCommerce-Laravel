@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
@endsection


<div class="product-grid container">
  @foreach ($products as $product)
    <div class="showcase">
      <div class="showcase-banner">
        <a href="{{ url('/product/' . $product->id) }}">
          <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" class="product-img default">
          <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" width="300" class="product-img hover">
        </a>
        <p class="showcase-badge">{{ $product->offer_percentage ?? '0' }}% Dynamic</p>
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

          <button class="btn-action remove-from-watch" data-id="{{ $product->id }}">
            <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="trash outline"></ion-icon>
          </button>
        </div>
      </div>
    </div>
  @endforeach
</div>


{{ $products->links('pagination::bootstrap-5') }}


@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection