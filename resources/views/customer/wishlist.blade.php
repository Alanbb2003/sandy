@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Wishlist</h3>
    <div class="col-sm-12 p-1 d-flex flex-wrap">
        {{-- @foreach ($WishlistItems as $k)
        <div class="card mb-4 p-1 mx-2" style="width: 180px;min-height: 260px">
          
            <a target="_blank" href="{{ asset('images/uploads/' . $k->fotoPromosi) }}">
              <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang">
            </a>
            <div class="card-body">
              <h5 class="card-title">{{ $k->namaBarang }}</h5>
              <p class="card-text">Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}</p>
              <a href="{{ url('/product/' . $k->slugBarang ) }}" class="btn btn-primary">Detail</a>


            <button class="wishlist-toggle btn" data-product-id="{{ $k->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
              @if(Auth::check())
                  @if(Auth::user()->wishlists->contains('fkProductID', $k->id))
                      <i class="fa-solid fa-heart"></i> <!-- Solid heart if in wishlist -->
                  @else
                      <i class="fa-regular fa-heart"></i> <!-- Regular heart if not in wishlist -->
                  @endif
              @else
                  <i class="fa-regular fa-heart"></i> <!-- Default regular heart for guests -->
              @endif
            </button>
            </div>
        </div>
        @endforeach --}}
        @foreach ($WishlistItems as $wishlistItem)
        <div class="card mb-4 p-1 mx-2 d-flex flex-column" style="width: 180px; min-height: 320px;">
            <!-- Product Image -->
            <a target="_blank" href="{{ asset('images/uploads/' . $wishlistItem->product->fotoPromosi) }}">
                <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $wishlistItem->product->fotoPromosi) }}" alt="Gambar Barang" loading="lazy">
            </a>
            <div class="card-body d-flex flex-column justify-content-between">
                <!-- Product Name -->
                <h5 class="card-title">{{ $wishlistItem->product->namaBarang }}</h5>
                <!-- Product Price -->
                <p class="card-text">Rp.{{ number_format($wishlistItem->product->hargaKecil, 0, ',', '.') }}</p>
               
              
                <div class="mt-auto d-flex justify-content-between align-items-center">
                        <!-- Product Detail Link -->
                    <a href="{{ url('/product/' . $wishlistItem->product->slugBarang) }}" class="btn btn-primary me-2 w-75">Detail</a>
                        <!-- Wishlist Toggle Button --> 
                    <button class="wishlist-toggle btn" data-product-id="{{ $wishlistItem->product->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                        @if(Auth::check())
                            @if(Auth::user()->wishlists->contains('fkProductID', $wishlistItem->product->id))
                                <i class="fa-solid fa-heart"></i> 
                            @else
                                <i class="fa-regular fa-heart"></i>
                            @endif
                        @else
                            <i class="fa-regular fa-heart"></i>
                        @endif
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    {{-- </div> --}}
</div>


{{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}


@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            var tooltip = new bootstrap.Tooltip(tooltipTriggerEl);
            
            tooltipTriggerEl.addEventListener('click', function () {
                // Hide tooltip after clicking
                tooltip.hide();
            });
            
            return tooltip;
        });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all wishlist buttons
        let wishlistButtons = document.querySelectorAll('.wishlist-toggle');
  
        wishlistButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get product ID from the button's data attribute
                let productId = this.getAttribute('data-product-id');
  
                @if(Auth::check())
                    // If the user is logged in, call the AJAX function to toggle wishlist
                    toggleWishlist(productId, this);
                @else
                    // If the user is not logged in, redirect to login page
                    window.location.href = '{{ route("login") }}';
                @endif
            });
        });
    });
  
    function toggleWishlist(productId, button) {
        // Make an AJAX request to toggle wishlist status
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            // Update the button based on the response
            if (data.in_wishlist) {
                button.innerHTML = '<i class="fa-solid fa-heart"></i>';
            } else {
                button.innerHTML = '<i class="fa-regular fa-heart"></i>';
            }
        })
        .catch(error => console.error('Error:', error));
    }
  </script>
@endsection