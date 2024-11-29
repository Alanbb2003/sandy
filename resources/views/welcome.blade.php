@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-auto" style="max-width: 800px; padding: 20px; border-radius: 8px;">
        <form action="{{ url()->current() }}" method="GET" class="row">
            <h3 class="mb-4">Cari Produk</h3>
    
            <!-- Product Name Filter -->
            <div class="col-12 mb-3">
                <label for="searchName" class="form-label">Nama Produk:</label>
                <input type="text" name="searchName" id="searchName" class="form-control" value="{{ request()->searchName }}" placeholder="Enter product name">
            </div>
    
            <div class="row">
                <!-- Category Filter -->
                <div class="col-md-4 mb-3">
                    <label for="categorySearch" class="form-label">Kategori:</label>
                    <select name="searchCategory" id="categorySearch" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($category as $a)
                            <option value="{{ $a->id }}" {{ request()->searchCategory == $a->id ? 'selected' : '' }}>
                                {{$a->nama_category}}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <!-- Min Price Filter -->
                <div class="col-md-4 mb-3">
                    <label for="minPrice" class="form-label">Min Price:</label>
                    <input type="number" name="minPrice" id="minPrice" class="form-control" value="{{ request()->minPrice }}" placeholder="Min price">
                </div>
    
                <!-- Max Price Filter -->
                <div class="col-md-4 mb-3">
                    <label for="maxPrice" class="form-label">Max Price:</label>
                    <input type="number" name="maxPrice" id="maxPrice" class="form-control" value="{{ request()->maxPrice }}" placeholder="Max price">
                </div>
            </div>
    
            <!-- Sorting Dropdown -->
            <div class="col-md-4 mb-3">
                <label for="sort" class="form-label">Urutkan:</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_high_to_low" {{ request()->sort == 'price_high_to_low' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    <option value="price_low_to_high" {{ request()->sort == 'price_low_to_high' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                </select>
            </div>
    
            <!-- Submit Button -->
            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary ms-2">Reset</a>
            </div>
        </form>
    </div>
    
<div class="d-flex justify-content-center align-items-center ps-4" style="min-height: 100vh;">
    <div class="row justify-content-center">
        @foreach ($category as $category)
            @php $categoryItems = $barang->where('fk_kategori', $category->id); @endphp
            @if ($categoryItems->isNotEmpty())
                <div class="col-12 mt-3">
                    <h3>{{ $category->nama_category }}</h3>
                </div>
                <div class="col-12 p-1 d-flex flex-wrap">
                    @foreach ($categoryItems as $k)
                        <div class="card mb-3 p-2 mx-2 d-flex flex-column align-items-center text-center" style="width: 180px; min-height: 350px;">
                            <a target="_blank" href="{{ url('/product/' . $k->slugBarang ) }}">
                                <img class="card-img-top img-thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang" loading="lazy" style="height: 160px; object-fit: cover;">
                            </a>
                            <div class="card-body d-flex flex-column text-center">
                                <div>
                                    <a href="{{ url('/product/' . $k->slugBarang ) }}" class="nodecor">
                                        <h6 class="card-title">{{ $k->namaBarang }}</h6>
                                    </a>
                                    <p class="card-text">Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}</p>
                                </div>

                                <div class="d-flex justify-content-center align-items-center mt-auto">
                                    <a href="{{ url('/product/' . $k->slugBarang ) }}" class="btn btn-primary w-75 me-1">Detail</a>
                                    <button class="wishlist-toggle btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" 
                                        data-product-id="{{ $k->id }}" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Add to Wishlist" 
                                        style="width: 40px; height: 40px; padding: 0;">
                                        @if(Auth::check())
                                            @if(Auth::user()->wishlists->contains('fkProductID', $k->id))
                                                <i class="fa-solid fa-heart text-danger"></i>
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
</div>
    <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
        <!-- Contact Info -->
        <div class="col mb-3">
          <h5>Contact</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2"><span class="text-muted">Email: <a href="mailto:info@tokosandy.com" class="text-decoration-none">info@tokosandy.com</a></span></li>
            {{-- <li class="nav-item mb-2"><span class="text-muted">Phone: +62 456-7890</span></li> --}}
            <li class="nav-item mb-2"><span class="text-muted">Address: Jln Lintas Seram, Kobisonta (Serut), Maluku</span></li>
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