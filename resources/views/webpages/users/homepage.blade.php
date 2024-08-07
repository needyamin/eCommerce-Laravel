@extends('layouts.users')
@section('title', 'Homepage')
@section('content')
@extends('re_usable_users.header')
@extends('re_usable_users.slider')

@section('shoping-cart-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
@endsection








<style>
.product {
  position: relative;
  display: inline-block;
  width: 300px; 
  height: 300px; 
}

.add-to-cart-container {
  display: none;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Optional: Add a background overlay */
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.product:hover .add-to-cart-container {
  display: flex; /* Use flexbox to align content */
}

.content-center-bottom {
  display: flex;
  flex-direction: column;
  align-items: center; 
  margin-bottom: 20px; 
}

.add-to-cart-btn {
  background-color: #ff5722;
  border: none;
  color: white;
  padding: 5px 10px;
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
  margin: 0 auto;
}
.add-to-cart{
  margin: 0 auto;
}

.cart-options .quantity {
  min-width: 20px;
  height: 100%;
  text-align: center;
  color: white;
}
.price-display {
  min-width: 20px;
  height: 100%;
  text-align: center;
  color: white;
}


.showcase-badge_new {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--ocean-green);
    font-size: var(--fs-8);
    font-weight: var(--weight-500);
    color: var(--white);
    padding: 0 8px;
    -webkit-border-radius: var(--border-radius-sm);
    border-radius: var(--border-radius-sm);
    z-index: 3;
}

  </style>



<div class="product-grid container-fluid">
@foreach ($products as $product)
  <div class="showcase">
    <div class="product showcase-banner" data-product-id="{{ $product->id }}">
      <img src="{{ asset('template/user/assets/images/products/jacket-4.jpg') }}" alt="{{ $product->product_name }}" width="300" class="product-img">
      <p class="showcase-badge_new">{{ $product->offer_percentage ?? '0' }}% Dynamic</p>
      <div class="add-to-cart-container">
      <div class="content-center-bottom">

      <div class="price-display"></div> <!-- Price display element -->
        <button class="add-to-cart-btn add-to-cart" data-id="{{ $product->id }}" data-quantity="1">Add to cart</button>
        <div class="cart-options" style="display: none;">
          <button class="decrease">-</button>
          <span class="quantity">0</span>
          <button class="increase">+</button>
        </div>
      </div>
     </div>
   
    <button class="btn mt-2 mb-5 btn-success w-100 add-to-cart" data-id="{{ $product->id }}" data-quantity="1">Add To Cart</button>

    </div>
  </div>
@endforeach
</div>

<div class="container mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  function initializeCartOptions() {
    const products = document.querySelectorAll('.product');

    products.forEach(product => {
      const productId = product.dataset.productId;
      const addToCartBtns = product.querySelectorAll('.add-to-cart');
      const cartOptions = product.querySelector('.cart-options');
      const quantitySpan = product.querySelector('.quantity');
      const priceDisplay = product.querySelector('.price-display');

      $.ajax({
        url: '/api/cart',
        method: 'GET',
        success: function(response) {
          if (response && typeof response === 'object') {
            const cartItems = Object.values(response);
            const cartItem = cartItems.find(item => item.id == productId);

            if (cartItem) {
              const price = cartItem.price;
              const quantity = cartItem.quantity;
              quantitySpan.textContent = quantity;
              cartOptions.style.display = 'flex';
              priceDisplay.textContent = `${(price * quantity).toFixed()} TK`;

              addToCartBtns.forEach(addToCartBtn => {
                if (addToCartBtn) {
                  console.log('Updating button for product ID:', productId);
                  addToCartBtn.textContent = 'Already Added';
                  addToCartBtn.classList.remove('btn-success');
                  addToCartBtn.classList.add('btn-secondary');
                  addToCartBtn.disabled = true;
                }
              });
            } else {
              quantitySpan.textContent = '0';
              cartOptions.style.display = 'none';

              addToCartBtns.forEach(addToCartBtn => {
                if (addToCartBtn) {
                  console.log('Resetting button for product ID:', productId);
                  addToCartBtn.textContent = 'Add To Cart';
                  addToCartBtn.classList.add('btn-success');
                  addToCartBtn.classList.remove('btn-secondary');
                  addToCartBtn.disabled = false;
                }
              });

              priceDisplay.textContent = '';
            }
          } else {
            console.error('Response data is not in expected format:', response);
          }
        },
        error: function(xhr) {
          console.error('Error fetching cart items:', xhr);
        }
      });
    });
  }

  function updateCartQuantity(productId, quantity) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
      url: `/api/cart/${productId}`,
      method: 'PATCH',
      data: { quantity: quantity },
      headers: { 'X-CSRF-TOKEN': csrfToken },
      success: function(response) {
        if (response && response.status === 'success') {
          updateCartCount();
          initializeCartOptions();
        } else {
          console.error('Error updating quantity:', response);
        }
      },
      error: function(xhr) {
        console.error('Error updating quantity:', xhr);
      }
    });
  }

  function removeFromCart(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
      url: `/api/cart/${productId}`,
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      success: function(response) {
        if (response && response.status === 'success') {
          updateCartCount();
          initializeCartOptions();
        } else {
          console.error('Error removing item from cart:', response);
        }
      },
      error: function(xhr) {
        console.error('Error removing item from cart:', xhr);
      }
    });
  }

  function updateCartCount() {
    $.ajax({
      url: '/api/cart',
      method: 'GET',
      success: function(response) {
        if (response && typeof response === 'object') {
          const cartItems = Object.values(response);
          const totalItems = cartItems.reduce((total, item) => total + parseInt(item.quantity, 10), 0);
          $('#total-cart-items').text(totalItems);
          $('#total-cart-items-mobile').text(totalItems);
        } else {
          console.error('Response data is not in expected format:', response);
        }
      },
      error: function(xhr) {
        console.error('There was an error fetching the cart count!', xhr);
      }
    });
  }

  initializeCartOptions();

  const addToCartBtns = document.querySelectorAll('.add-to-cart');

  addToCartBtns.forEach(button => {
    button.addEventListener('click', (event) => {
      const button = event.target;
      const container = button.closest('.product');

      if (!container) {
        console.error('Container element not found for button with ID:', button.dataset.id);
        return;
      }

      const cartOptions = container.querySelector('.cart-options');
      const quantitySpan = container.querySelector('.quantity');
      const productId = button.dataset.id;

      if (button.textContent.trim() === 'Add To Cart') {
        quantitySpan.textContent = '1';
        updateCartQuantity(productId, 1);
      }
    });
  });

  const products = document.querySelectorAll('.product');

  products.forEach(product => {
    const decreaseBtn = product.querySelector('.decrease');
    const increaseBtn = product.querySelector('.increase');
    const quantitySpan = product.querySelector('.quantity');
    const productId = product.dataset.productId;

    if (decreaseBtn && increaseBtn && quantitySpan) {
      decreaseBtn.addEventListener('click', () => {
        let quantity = parseInt(quantitySpan.textContent);
        if (quantity > 0) {
          quantity--;
          quantitySpan.textContent = quantity;

          updateCartQuantity(productId, quantity);

          if (quantity === 0) {
            removeFromCart(productId);
          }
        }
      });

      increaseBtn.addEventListener('click', () => {
        let quantity = parseInt(quantitySpan.textContent);
        quantity++;
        quantitySpan.textContent = quantity;

        updateCartQuantity(productId, quantity);
      });
    }
  });
});
</script>










<hr>




<!-- OLD CODE -->
<!-- <div class="product-grid container">
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
</div> -->


<!-- <div class="container mt-3">{{ $products->links('pagination::bootstrap-5') }}</div> -->

@extends('re_usable_users.sidebar')
@extends('re_usable_users.footer')
@endsection
