@extends(Auth::guest() ? 'layouts.app' : (Auth::user()->role == 'user' ? 'layouts.app' : 'layouts.appAdmin'))

@section('content')
<div class="container mt-5">
    <div class="mb-3">
        @if (Auth::check() && Auth::user()->role == 'admin')
        <a href="/dashboard/barang" class="mx-5"><i class="fa-solid fa-circle-arrow-left fa-xl"></i></a>
        @elseif (Auth::guest())
        <a href="/" class="mx-5"><i class="fa-solid fa-circle-arrow-left fa-xl"></i></a>
        @else
        <a href="/" class="mx-5"><i class="fa-solid fa-circle-arrow-left fa-xl"></i></a>
        @endif
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div id="productCarousel" class="carousel slide shadow-sm mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="{{ asset('images/uploads/'.$barang->fotoPromosi) }}" alt="Gambar Barang" style="max-height: 600px; object-fit: contain;">
                    </div>
                    @foreach ($pic as $index => $p)
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('images/uploads/'.$p->fileName) }}" alt="Gambar Barang" style="max-height: 600px; object-fit: contain;">
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

            <div class="row mt-2 gx-2">
                <div class="col-3">
                    <img class="img-thumbnail rounded shadow-sm" src="{{ asset('images/uploads/'.$barang->fotoPromosi) }}" alt="Gambar Barang" data-bs-target="#productCarousel" data-bs-slide-to="0" style="max-height: 200px; object-fit: contain;">
                </div>
                @foreach ($pic as $index => $p)
                <div class="col-3">
                    <img class="img-thumbnail rounded shadow-sm" src="{{ asset('images/uploads/'.$p->fileName) }}" alt="Gambar Barang" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index + 1 }}" style="max-height: 200px; object-fit: contain;">
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-start">
                <h3>{{ $barang->namaBarang }}</h3>
                @if (Auth::check() && Auth::user()->role == 'user')
                    <button class="wishlist-toggle btn btn-outline-danger" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" title="Add to Wishlist">
                        @if(Auth::user()->wishlists->contains('fkProductID', $barang->id))
                            <i class="fa-solid fa-heart"></i> 
                        @else
                            <i class="fa-regular fa-heart"></i>
                        @endif
                    </button>
                @elseif (Auth::guest())
                    <button class="wishlist-toggle btn btn-outline-danger" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" title="Add to Wishlist">
                        <i class="fa-regular fa-heart"></i> 
                    </button>
                @endif
            </div>

            

 
            <div class="mb-4 bg-light">
                <p class="text-muted mt-3">{!! nl2br(e($barang->deskripsi)) !!}</p>
                <h5>Harga:</h5>
                <p>Rp. {{ number_format($barang->hargaKecil, 2, ",", ".") }} per {{ $barang->satuanTerkecil }}</p>
                @if($barang->satuanBesar && $barang->hargaBesar)
                    <p>Rp. {{ number_format($barang->hargaBesar, 2, ",", ".") }} per {{ $barang->satuanBesar }}</p>
                @endif
            </div>
            
               @if($barang->Status == 2)
                <div class="alert alert-danger">
                    <strong>Produk sedang tidak tersedia.</strong>
                </div>
            @else
                <form action="{{ url('/cart/add') }}" method="POST" class="bg-light p-3 rounded shadow-sm">
                    @csrf
                    <input type="hidden" id="IDbarang" name="IDbarang" value="{{ $barang->id }}">

                    <div class="mb-3">
                        <label for="quantity_{{ $barang->id }}" class="form-label">Jumlah:</label>
                        <input type="number" id="quantity_{{ $barang->id }}" name="quantity" min="1" value="1" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="unit_{{ $barang->id }}" class="form-label">Satuan:</label>
                        <select id="unit_{{ $barang->id }}" name="unit" class="form-select">
                            <option value="small">{{ $barang->satuanTerkecil }}</option>
                            @if($barang->satuanBesar)
                                <option value="big">{{ $barang->satuanBesar }}</option>
                            @endif
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Tambah ke Keranjang</button>
                </form>
            @endif
        </div>
    </div>
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