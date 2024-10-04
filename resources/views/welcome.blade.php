@extends('layouts.app')

@section('content')
<div class="container">
  <div>
    <form action="{{ url()->current() }}" method="GET">
        <h3>Search Products</h3>
        
        <!-- Category Filter -->
        <label for="categorySearch">Category:</label>
        <select name="searchCategory" id="categorySearch" class="form-select">
            <option value="">All Categories</option>
            @foreach ($category as $a)
                <option value="{{ $a->id }}" {{ request()->searchCategory == $a->id ? 'selected' : '' }}>
                    {{$a->nama_category}}
                </option>
            @endforeach
        </select>

        <!-- Name Filter -->
        <label for="searchName">Product Name:</label>
        <input type="text" name="searchName" id="searchName" class="form-control" value="{{ request()->searchName }}" placeholder="Enter product name">

        <!-- Price Filter -->
        <label for="minPrice">Min Price:</label>
        <input type="number" name="minPrice" id="minPrice" class="form-control" value="{{ request()->minPrice }}" placeholder="Min price">
        
        <label for="maxPrice">Max Price:</label>
        <input type="number" name="maxPrice" id="maxPrice" class="form-control" value="{{ request()->maxPrice }}" placeholder="Max price">

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>
  </div>
    
    @include('customer.rowBarang')

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