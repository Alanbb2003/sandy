@extends('layouts.app')

@section('content')
<div class="container">
    <div>
      <form action="">
        <h3>search form disini</h3>
        <select name="searchCategory" id="categorySearch">
              @foreach ($category as $a)
              <option value="{{ $a->id }}">
                  {{$a->nama_category}}
              </option>
              @endforeach
      </select>
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
{{-- <script>
$(document).ready(function(){
    $(document).on('click', '.detail-product', function() {
        // Extract data from the button
        var productName = $(this).data('product-name');
        var productId = $(this).data('product-id');

        // Encode the product name to ensure it works in the URL
        var encodedProductName = encodeURIComponent(productName);

        // Create a form dynamically
        var form = $('<form>', {
            'action': '/product/' + encodedProductName,
            'method': 'GET'
        });

        // Add the CSRF token to the form
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': csrfToken
        }));

        // Add the product ID to the form
        form.append($('<input>', {
            'type': 'hidden',
            'name': 'productId',
            'value': productId
        }));

        // Append the form to the body and submit it
        $('body').append(form);
        form.submit();
    });
  });
</script> --}}
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