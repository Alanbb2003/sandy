@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Your Cart</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                        <tr>
                            <td>
                                <img src="{{ asset('images/uploads/'.$details['image']) }}" 
                                     class="img-thumbnail" 
                                     style="width: 50px; height: auto;" 
                                     alt="Product Image" />
                            </td>
                            <td>{{ $details['name'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ url('/cart/addOne/'.$id) }}" class="btn btn-sm btn-success me-1">
                                        <i class="fa-solid fa-plus"></i>
                                    </a>
                                    <span>{{ $details['quantity'] }}</span>
                                    <a href="{{ url('/cart/removeOne/'.$id) }}" class="btn btn-sm btn-danger ms-1">
                                        <i class="fa-solid fa-minus"></i>
                                    </a>
                                </div>
                            </td>
                            <td>{{ $details['unit'] }}</td>
                            <td>Rp.{{ number_format($details['price'], 0, ',', '.') }}</td>
                            <td>Rp.{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ url('/cart/remove/'.$id) }}" class="btn btn-sm btn-danger">
                                    Remove
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">Your cart is empty.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3">
        <h3>Total:</h3>
        <h3 id="totalAmountDisplay">Rp.{{ number_format($totalAmmount, 2, ',', '.') }}</h3>
        <a href="{{ url('/checkout') }}" class="btn btn-primary mt-2">Checkout</a>
    </div>
</div>
@endsection

@section('script')
<script>

</script>
@endsection