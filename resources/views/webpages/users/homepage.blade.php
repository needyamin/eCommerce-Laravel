@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
@endsection








  <style>
.product {
  position: relative;
  display: inline-block;
}

.add-to-cart-container {
  display: none;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  background: rgba(0, 0, 0, 0.5); /* Light dark background */
  padding: 10px;
  border-radius: 5px;
}

.product:hover .add-to-cart-container {
  display: block;
}

.add-to-cart-btn {
  background-color: #ff5722;
  border: none;
  color: white;
  padding: 10px 20px;
  cursor: pointer;
}

.cart-options {
  display: none;
  margin-top: 10px;
}

.cart-options button {
  background-color: #ff5722;
  border: none;
  color: white;
  padding: 5px 10px;
  margin: 0 5px;
  cursor: pointer;
}

.cart-options .quantity {
  min-width: 20px;
  height: 100%;
  text-align: center;
  color: white;
}

  </style>




@foreach ($products as $product)
  <div class="product card">
    <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" width="300" class="product-img">       
    <p class="showcase-badge">{{ $product->offer_percentage ?? '0' }}% Dynamic</p>
    <div class="add-to-cart-container">
      <button class="add-to-cart-btn">Add to cart</button>
      <div class="cart-options">
        <button class="decrease">-</button>
        <span class="quantity">0</span>
        <button class="increase">+</button>
      </div>
    </div>
    <button class="btn btn-success w-100" data-id="{{ $product->id }}" data-quantity="1">Add To Cart</button>
  </div>
@endforeach

<div class="container mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');

    addToCartBtns.forEach(button => {
      button.addEventListener('click', (event) => {
        const container = event.target.parentElement;
        const cartOptions = container.querySelector('.cart-options');
        const addToCartBtn = container.querySelector('.add-to-cart-btn');

        addToCartBtn.style.display = 'none';
        cartOptions.style.display = 'flex';
      });
    });

    const products = document.querySelectorAll('.product');

    products.forEach(product => {
      const decreaseBtn = product.querySelector('.decrease');
      const increaseBtn = product.querySelector('.increase');
      const quantitySpan = product.querySelector('.quantity');

      if (decreaseBtn && increaseBtn && quantitySpan) {
        decreaseBtn.addEventListener('click', () => {
          let quantity = parseInt(quantitySpan.textContent);
          if (quantity > 0) {
            quantity--;
            quantitySpan.textContent = quantity;
          }
        });

        increaseBtn.addEventListener('click', () => {
          let quantity = parseInt(quantitySpan.textContent);
          quantity++;
          quantitySpan.textContent = quantity;
        });
      }
    });
  });
</script>






<hr>




<!-- OLD CODE -->
<div class="product-grid container">
  @foreach ($products as $product)
    <div class="showcase">
      <div class="showcase-banner">
          <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" class="product-img default">
          <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" width="300" class="product-img hover">
        <p class="showcase-badge">{{ $product->offer_percentage ?? '0' }}% Dynamic</p>
        <div class="showcase-actions">
          <button class="btn-action add-to-watchlist" data-id="{{ $product->id }}" data-quantity="1">
            <ion-icon name="heart-outline" role="img" class="md hydrated" aria-label="heart outline"></ion-icon>
          </button>
          <button class="btn-action">
            <a href="{{ url('/product/' . $product->id) }}">
            <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
            </a>
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


<div class="container mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>

@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection