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
<div class="container mt-4">
    
    <div class="row">
        
        <div class="col">
            {{-- <div class="col"> --}}
                <div class="row">
                    <img class="thumbnail" src="{{asset('images/uploads/'.$barang->fotoPromosi)}}" alt="Gambar Barang">
                </div>
                <div class="row">
                    @foreach ($pic as $p)
                    <img class="thumbnail" src="{{asset('images/uploads/'.$p->fileName)}}" alt="Gambar Barang">
                    @endforeach
                </div>
            {{-- </div> --}}
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
                    {{-- <button class="wishlist-toggle btn" data-product-id="{{ $barang->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                        @if(Auth::check())
                            @if(Auth::user()->wishlists->contains('fkProductID', $barang->id))
                                <i class="fa-solid fa-heart"></i> <!-- Solid heart if in wishlist -->
                            @else
                                <i class="fa-regular fa-heart"></i> <!-- Regular heart if not in wishlist -->
                            @endif
                        @else
                            <i class="fa-regular fa-heart"></i> <!-- Default regular heart for guests -->
                        @endif
                    </button> --}}
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
                    {{-- <button class="btn btn-primary add-to-cart">Add to Cart</button> --}}
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
                {{-- <form action="{{url('/cart/add') }}" method="POST">
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
                </form> --}}
            </div>
            {{-- <div class="container">
                <h2>Your Cart</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                                <tr>
                                    <td><img src="{{ asset('images/uploads/'.$details['image'] )}}" width="50" height="50" class="img-thumbnail" /></td>
                                    <td>{{ $details['name'] }}</td>
                                    <td>{{ $details['quantity'] }}</td>
                                    <td>{{ $details['unit'] }}</td>
                                    <td>${{ $details['price'] }}</td>
                                    <td>${{ $details['price'] * $details['quantity'] }}</td>
                                    <td>
                                        <a class="btn btn-danger remove-from-cart" href="{{url('/cart/remove/'.$id )}}">Remove</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div> --}}
            

            {{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}
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