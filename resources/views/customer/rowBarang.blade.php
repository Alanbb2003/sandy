{{-- 
<div class="row justify-content-center">
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
          <div class="card mb-4 p-1 mx-2" style="width: 180px;min-height: 260px">
            
              <a target="_blank" href="{{ asset('images/uploads/' . $k->fotoPromosi) }}">
                <img class="card-img-top thumbnail" src="{{ asset('images/uploads/' . $k->fotoPromosi) }}" alt="Gambar Barang" loading="lazy">
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
          @endforeach
      </div>
  @endif
  @endforeach
</div> --}}

<div class="row justify-content-center">
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