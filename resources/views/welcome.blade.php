@extends('layouts.app')

@section('content')
<div class="container">
    <div style="width: 60%; margin: auto; padding: 20px; border-radius: 8px;">
        <form action="{{ url()->current() }}" method="GET" class="row">
            <h3>Search Products</h3>
    
            <!-- Product Name Filter -->
            <div class="col-12">
                <label for="searchName" class="form-label">Nama Produk:</label>
                <input type="text" name="searchName" id="searchName" class="form-control" value="{{ request()->searchName }}" placeholder="Enter product name">
            </div>
    
            <div class="row">
                <!-- Category Filter -->
                <div class="col-md-4">
                    <label for="categorySearch" class="form-label">Kategori:</label>
                    <select name="searchCategory" id="categorySearch" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($category as $a)
                            <option value="{{ $a->id }}" {{ request()->searchCategory == $a->id ? 'selected' : '' }}>
                                {{$a->nama_category}}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <!-- Min Price Filter -->
                <div class="col-md-4">
                    <label for="minPrice" class="form-label">Min Price:</label>
                    <input type="number" name="minPrice" id="minPrice" class="form-control" value="{{ request()->minPrice }}" placeholder="Min price">
                </div>
    
                <!-- Max Price Filter -->
                <div class="col-md-4">
                    <label for="maxPrice" class="form-label">Max Price:</label>
                    <input type="number" name="maxPrice" id="maxPrice" class="form-control" value="{{ request()->maxPrice }}" placeholder="Max price">
                </div>
            </div>
    
            <!-- Sorting Dropdown -->
            <div class="col-md-4 mt-3">
                <label for="sort" class="form-label">Urutkan:</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_high_to_low" {{ request()->sort == 'price_high_to_low' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="price_low_to_high" {{ request()->sort == 'price_low_to_high' ? 'selected' : '' }}>Price: Low to High</option>
                </select>
            </div>
    
            <!-- Submit Button -->
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary mt-3 ms-2">Reset</a>
            </div>
        </form>
    </div>
    
    <div class="row justify-content-center ms-5">
        @foreach ($category as $category)
        @php
            $categoryItems = $barang->where('fk_kategori', $category->id);
        @endphp
      
        @if ($categoryItems->isNotEmpty())
            <div class="col-12 mt-3">
                <h3>{{ $category->nama_category }}</h3> 
            </div>
            <div class="col-sm-12 p-1 d-flex flex-wrap">
                @foreach ($categoryItems as $k)
                <div class="card mb-4 p-1 mx-2 d-flex flex-column" style="width: 180px; min-height: 320px;"> 
                    <a target="_blank" href="{{ url('/product/' . $k->slugBarang ) }}">
                      <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang" loading="lazy">
                    </a>
                    <div class="card-body d-flex flex-column justify-content-between"> 
                      <div>
                       <a href="{{ url('/product/' . $k->slugBarang ) }}" class="nodecor"><h5 class="card-title">{{ $k->namaBarang }}</h5></a> 
                        <p class="card-text">Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}</p>
                      </div>
    
                      <div class="mt-auto d-flex justify-content-between align-items-center"> 
                        <a href="{{ url('/product/' . $k->slugBarang ) }}" class="btn btn-primary me-2 w-75">Detail</a> 
                        
                        <button class="wishlist-toggle btn btn-outline-secondary w-25" data-product-id="{{ $k->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                          @if(Auth::check())
                              @if(Auth::user()->wishlists->contains('fkProductID', $k->id))
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
        @endif
        @endforeach
    </div>
      

    <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
        <div class="col mb-3">
          <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
          </a>
          <p class="text-body-secondary">&copy; 2024</p>
        </div>
    
        <div class="col mb-3">
          <h5>Section</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Features</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Pricing</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">About</a></li>
          </ul>
        </div>

      </footer>
</div>

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
    document.querySelectorAll('.scroll-container').forEach((container, index) => {
        // Get the left and right buttons for each container
        const scrollLeftButton = document.querySelectorAll('.scroll-left')[index];
        const scrollRightButton = document.querySelectorAll('.scroll-right')[index];

        // Scroll left on button click
        scrollLeftButton.addEventListener('click', () => {
            container.scrollBy({
                left: -300, // Scroll 300px to the left
                behavior: 'smooth'
            });
        });

        // Scroll right on button click
        scrollRightButton.addEventListener('click', () => {
            container.scrollBy({
                left: 300, // Scroll 300px to the right
                behavior: 'smooth'
            });
        });
    });

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