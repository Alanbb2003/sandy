<div class="row justify-content-center ms-5">
  @foreach ($category as $category)
  @php
      $categoryItems = $barang->where('fk_kategori', $category->id);
  @endphp

  @if ($categoryItems->isNotEmpty())
      <div class="col-12 mt-3">
          <h3>{{ $category->nama_category }}</h3> <!-- Category name -->
      </div>
      <div class="col-sm-12 p-1 d-flex flex-wrap">
          @foreach ($categoryItems as $k)
          <div class="card mb-4 p-1 mx-2 d-flex flex-column" style="width: 180px; min-height: 320px;"> <!-- Set a minimum consistent height -->
              <a target="_blank" href="{{ asset('images/uploads/' . $k->fotoPromosi) }}">
                <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang" loading="lazy">
              </a>
              <div class="card-body d-flex flex-column justify-content-between"> <!-- Flexbox to space out the content -->
                <div>
                  <h5 class="card-title">{{ $k->namaBarang }}</h5>
                  <p class="card-text">Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}</p>
                </div>

                <div class="mt-auto d-flex justify-content-between align-items-center"> <!-- Align buttons next to each other -->
                  <a href="{{ url('/product/' . $k->slugBarang ) }}" class="btn btn-primary me-2 w-75">Detail</a> <!-- 75% width for "Detail" button -->
                  
                  <button class="wishlist-toggle btn btn-outline-secondary w-25" data-product-id="{{ $k->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
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
          </div>
          @endforeach
      </div>
  @endif
  @endforeach
</div>

{{-- <div class="row justify-content-center mx-4">
  @foreach ($category as $category)
  @php
      $categoryItems = $barang->where('fk_kategori', $category->id);
  @endphp

  @if ($categoryItems->isNotEmpty())
      <div class="col-12 mt-3">
          <h3>{{ $category->nama_category }}</h3> <!-- Category name -->
      </div>

      <!-- Scrollable container with a max height of 400px (you can adjust as needed) -->
      <div class="col-sm-12 p-1 d-flex flex-wrap overflow-auto" style="max-height: 400px;">
          @foreach ($categoryItems as $k)
          <div class="card mb-4 p-1 mx-2 d-flex flex-column" style="width: 180px; min-height: 320px;"> <!-- Set a minimum consistent height -->
              <a target="_blank" href="{{ asset('images/uploads/' . $k->fotoPromosi) }}">
                <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang" loading="lazy">
              </a>
              <div class="card-body d-flex flex-column justify-content-between"> <!-- Flexbox to space out the content -->
                <div>
                  <h5 class="card-title">{{ $k->namaBarang }}</h5>
                  <p class="card-text">Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}</p>
                </div>

                <div class="mt-auto d-flex justify-content-between align-items-center"> <!-- Align buttons next to each other -->
                  <a href="{{ url('/product/' . $k->slugBarang ) }}" class="btn btn-primary me-2 w-75">Detail</a> <!-- 75% width for "Detail" button -->
                  
                  <button class="wishlist-toggle btn btn-outline-secondary w-25" data-product-id="{{ $k->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
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
          </div>
          @endforeach
      </div> <!-- End of scrollable container -->
  @endif
  @endforeach
</div> --}}

{{-- <div class="row justify-content-center mx-4">
  @foreach ($category as $category)
  @php
      $categoryItems = $barang->where('fk_kategori', $category->id);
  @endphp

  @if ($categoryItems->isNotEmpty())
      <div class="col-12 mt-3">
          <h3>{{ $category->nama_category }}</h3> <!-- Category name -->
      </div>

      <!-- Scroll buttons and container -->
      <div class="col-12 d-flex align-items-center position-relative">
          <!-- Left scroll button -->
          <button class="btn scroll-left" style="height: 40px; width: 40px; border-radius: 50%; background-color: #007bff; color: white; border: none;">
              <i class="fa fa-chevron-left"></i>
          </button>

          <!-- Scrollable container with a max height of 400px and hidden scrollbar -->
          <div class="col p-1 d-flex flex-wrap overflow-hidden position-relative">
              <div class="scroll-container d-flex overflow-auto" style="scroll-behavior: smooth; max-height: 400px; scrollbar-width: none; -ms-overflow-style: none;">
                  @foreach ($categoryItems as $k)
                  <div class="card mb-4 p-1 mx-2 d-flex flex-column" style="width: 180px; min-height: 320px; flex-shrink: 0;"> <!-- Maintain consistent size and prevent shrinking -->
                      <a target="_blank" href="{{ asset('images/uploads/' . $k->fotoPromosi) }}">
                          <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang" loading="lazy">
                      </a>
                      <div class="card-body d-flex flex-column justify-content-between">
                          <div>
                              <h5 class="card-title">{{ $k->namaBarang }}</h5>
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
          </div>

          <!-- Right scroll button -->
          <button class="btn scroll-right" style="height: 40px; width: 40px; border-radius: 50%; background-color: #007bff; color: white; border: none;">
              <i class="fa fa-chevron-right"></i>
          </button>
      </div>
  @endif
  @endforeach
</div> --}}