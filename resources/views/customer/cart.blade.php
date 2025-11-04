@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center mb-4">Cart Saya</h2>
    @if(session('cart'))
        <div class="row gy-4">
            @foreach(session('cart') as $id => $details)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="row g-0 align-items-center">
                            <div class="col-4">
                                <img src="{{ asset('images/uploads/'.$details['image']) }}" 
                                     class="img-fluid rounded-start w-100 h-auto" 
                                     alt="Product Image">
                            </div>
                            <div class="col-8">
                                <div class="card-body d-flex flex-column justify-content-between h-100">
                                    <h5 class="card-title">{{ $details['name'] }}</h5>
                                    <p class="card-text mb-2">
                                        <strong>Harga:</strong> Rp.{{ number_format($details['price'], 0, ',', '.') }} <br>
                                        <strong>Jumlah:</strong> 
                                        <div class="d-flex align-items-center mt-1">
                                            <a href="{{ url('/cart/addOne/'.$id) }}" class="btn btn-sm btn-success me-2">
                                                <i class="fa-solid fa-plus"></i>
                                            </a>
                                            <span>{{ $details['quantity'] }}</span>
                                            <a href="{{ url('/cart/removeOne/'.$id) }}" class="btn btn-sm btn-danger ms-2">
                                                <i class="fa-solid fa-minus"></i>
                                            </a>
                                        </div>
                                    </p>
                                    <p class="card-text">
                                        <strong>Satuan:</strong> {{ $details['unit'] }} <br>
                                        <strong>Total:</strong> Rp.{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                    </p>
                                    <a href="{{ url('/cart/remove/'.$id) }}" class="btn btn-outline-danger btn-sm mt-2">
                                        Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 text-end">
            <h4>Total:</h4>
            <h4 id="totalAmountDisplay">Rp.{{ number_format($totalAmmount, 2, ',', '.') }}</h4>
            <a href="{{ url('/checkout') }}" class="btn btn-primary mt-3">Checkout</a>
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">Cart Anda Kosong.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Lihat Produk</a>
        </div>
    @endif
</div>
@endsection

@section('script')
<script>

</script>
@endsection