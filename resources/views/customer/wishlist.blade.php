@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Wishlist</h3>
    <div class="col-sm-12 p-1 d-flex flex-wrap">
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
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            var tooltip = new bootstrap.Tooltip(tooltipTriggerEl);
            
            tooltipTriggerEl.addEventListener('click', function () {
                tooltip.hide();
            });
            
            return tooltip;
        });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        let wishlistButtons = document.querySelectorAll('.wishlist-toggle');
  
        wishlistButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                let productId = this.getAttribute('data-product-id');
  
                @if(Auth::check())
                    toggleWishlist(productId, this);
                @else
                    window.location.href = '{{ route("login") }}';
                @endif
            });
        });
    });
  
    function toggleWishlist(productId, button) {
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