{{-- @extends('layouts.app') --}}
@extends(Auth::guest() ? 'layouts.app' : (Auth::user()->role == 'user' ? 'layouts.app' : 'layouts.appAdmin'))

@section('content')
@if (Auth::check() && Auth::user()->role == 'admin')
<a href="/dashboard/barang" class="mx-5"><i class="fa-solid fa-circle-arrow-left fa-xl"></i></a>
@elseif (Auth::guest())
<a href="/" class="mx-5"><i class="fa-solid fa-circle-arrow-left fa-xl"></i></a>
@else
<a href="/" class="mx-5"><i class="fa-solid fa-circle-arrow-left fa-xl"></i></a>
@endif
{{-- <div class="container mt-4">
    <div class="row">
        <div class="col">
                <div class="row">
                    <img class="thumbnail" src="{{asset('images/uploads/'.$barang->fotoPromosi)}}" alt="Gambar Barang">
                </div>
                <div class="row">
                    @foreach ($pic as $p)
                    <img class="thumbnail" src="{{asset('images/uploads/'.$p->fileName)}}" alt="Gambar Barang">
                    @endforeach
                </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col">
                    <h3>{{ $barang->namaBarang }}</h3>
                </div>
                <div class="col">
                    
                    @if (Auth::check() && Auth::user()->role == 'user')
                        <button class="wishlist-toggle btn" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                            @if(Auth::user()->wishlists->contains('fkProductID', $barang->id))
                                <i class="fa-solid fa-heart"></i>
                            @else
                                <i class="fa-regular fa-heart"></i> 
                            @endif
                        </button>
                    @elseif (Auth::guest())
                        <button class="wishlist-toggle btn" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                            <i class="fa-regular fa-heart"></i> 
                        </button>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <h5>Deskripsi:</h5>
                
                <p>{!! nl2br(e($barang->deskripsi)) !!}</p>
            </div>
            
            <h5>Price:</h5>
            <p>Rp.{{ $barang->hargaKecil }} per 1 {{$barang->satuanTerkecil}}</p>
            @if($barang->satuanBesar && $barang->hargaBesar)
            <p>Rp.{{ $barang->hargaBesar }} per  1 {{$barang->satuanBesar}}</p>
            @endif
            
            <div>
                @if (Auth::check() && Auth::user()->role == 'user')
                <form action="{{url('/cart/add') }}" method="POST">
                    @csrf
                    <input type="hidden" id="IDbarang" name="IDbarang" value="{{$barang->id}}">

                    <label for="quantity_{{ $barang->id }}">Quantity:</label>
                    <input type="number" id="quantity_{{ $barang->id }}" name="quantity" min="1" value="1" class="form-control">
                    
                    <label for="unit_{{ $barang->id }}">Unit:</label>
                    <select id="unit_{{ $barang->id }}" name="unit" class="form-control">
                        <option value="small">{{ $barang->satuanTerkecil }}</option>
                        @if($barang->satuanBesar)
                        <option value="big">{{ $barang->satuanBesar }}</option>
                        @endif
                    </select>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Tambah ke keranjang</button>
                    </div>
                </form>
                @elseif (Auth::guest())
                <form action="{{url('/cart/add') }}" method="POST">
                    @csrf
                    <input type="hidden" id="IDbarang" name="IDbarang" value="{{$barang->id}}">

                    <label for="quantity_{{ $barang->id }}">Quantity:</label>
                    <input type="number" id="quantity_{{ $barang->id }}" name="quantity" min="1" value="1" class="form-control">
                    
                    <label for="unit_{{ $barang->id }}">Unit:</label>
                    <select id="unit_{{ $barang->id }}" name="unit" class="form-control">
                        <option value="small">{{ $barang->satuanTerkecil }}</option>
                        @if($barang->satuanBesar)
                        <option value="big">{{ $barang->satuanBesar }}</option>
                        @endif
                    </select>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Tambah ke keranjang</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div> --}}

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <!-- Main Carousel with reduced height -->
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- Main promotion image -->
                    <div class="carousel-item active">
                        <img class="d-block w-100 main-carousel-image" src="{{ asset('images/uploads/'.$barang->fotoPromosi) }}" alt="Gambar Barang">
                    </div>
                    <!-- Other product images -->
                    @foreach ($pic as $index => $p)
                    <div class="carousel-item">
                        <img class="d-block w-100 main-carousel-image" src="{{ asset('images/uploads/'.$p->fileName) }}" alt="Gambar Barang">
                    </div>
                    @endforeach
                </div>
                <!-- Carousel controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Thumbnails Row -->
            <div class="row mt-3">
                <!-- Thumbnail for the main image -->
                <div class="col-3">
                    <img class="img-thumbnail thumbnail-image" src="{{ asset('images/uploads/'.$barang->fotoPromosi) }}" alt="Gambar Barang" data-bs-target="#productCarousel" data-bs-slide-to="0">
                </div>
                <!-- Thumbnails for other images -->
                @foreach ($pic as $index => $p)
                <div class="col-3">
                    <img class="img-thumbnail thumbnail-image" src="{{ asset('images/uploads/'.$p->fileName) }}" alt="Gambar Barang" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index + 1 }}">
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-6">
            <!-- Product Information and Wishlist -->
            <div class="row">
                <div class="col">
                    <h3>{{ $barang->namaBarang }}</h3>
                </div>
                <div class="col">
                    @if (Auth::check() && Auth::user()->role == 'user')
                        <button class="wishlist-toggle btn" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                            @if(Auth::user()->wishlists->contains('fkProductID', $barang->id))
                                <i class="fa-solid fa-heart"></i> <!-- Solid heart if in wishlist -->
                            @else
                                <i class="fa-regular fa-heart"></i> <!-- Regular heart if not in wishlist -->
                            @endif
                        </button>
                    @elseif (Auth::guest())
                        <button class="wishlist-toggle btn" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                            <i class="fa-regular fa-heart"></i> <!-- Default regular heart for guests -->
                        </button>
                    @endif
                </div>
            </div>
            <!-- Rest of the content -->
            <div class="row mt-3">
                <h5>Deskripsi:</h5>
                <p>{!! nl2br(e($barang->deskripsi)) !!}</p>
            </div>
            <h5>Price:</h5>
            <p>Rp.{{ $barang->hargaKecil }} per 1 {{$barang->satuanTerkecil}}</p>
            @if($barang->satuanBesar && $barang->hargaBesar)
            <p>Rp.{{ $barang->hargaBesar }} per 1 {{$barang->satuanBesar}}</p>
            @endif

            <div>
                <!-- Add to cart form -->
                @if (Auth::check() && Auth::user()->role == 'user')
                <form action="{{ url('/cart/add') }}" method="POST">
                    @csrf
                    <input type="hidden" id="IDbarang" name="IDbarang" value="{{ $barang->id }}">

                    <label for="quantity_{{ $barang->id }}">Quantity:</label>
                    <input type="number" id="quantity_{{ $barang->id }}" name="quantity" min="1" value="1" class="form-control">

                    <label for="unit_{{ $barang->id }}">Unit:</label>
                    <select id="unit_{{ $barang->id }}" name="unit" class="form-control">
                        <option value="small">{{ $barang->satuanTerkecil }}</option>
                        @if($barang->satuanBesar)
                        <option value="big">{{ $barang->satuanBesar }}</option>
                        @endif
                    </select>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Tambah ke keranjang</button>
                    </div>
                </form>
                @endif
            </div>
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
                // Hide tooltip after clicking
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