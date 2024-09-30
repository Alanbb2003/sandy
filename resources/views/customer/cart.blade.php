@extends('layouts.app')

@section('content')
<div class="container">
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
                        <td>
                            <a href="{{url('/cart/addOne/'.$id )}}" class="btn nodecor mb-2">+</a>
                            {{ $details['quantity'] }}
                            <a href="{{url('/cart/removeOne/'.$id )}}" class="btn nodecor mb-2">-</a>
                        </td>
                        <td>{{ $details['unit'] }}</td>
                        <td>Rp.{{ number_format($details['price'], 0, ',', '.') }}</td>
                        <td>Rp.{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                        <td>
                            <a class="btn btn-danger remove-from-cart nodecor" href="{{url('/cart/remove/'.$id )}}">Remove</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <h3>Total:</h3>
    <h3 id="totalAmountDisplay"> Rp.{{ number_format($totalAmmount, 2, ",", ".") }} </h3>
    {{-- <div class="col">
        <div class="row">
            @if(session('cart'))
           
            @foreach(session('cart') as $id => $details)
            
                <div class="col">
                    <img src="{{ asset('images/uploads/'.$details['image'] )}}" width="50" height="50" class="thumbnailBig" />
                </div>
                
                <div class="col">
                    <p>{{ $details['name'] }}</p>
                    <div class="row justify-content-center">

                        <a href="{{url('/cart/removeOne/'.$id )}}" class="btn btn-info nodecor">-</a>

                        <p>QTY :{{ $details['quantity'] }} {{ $details['unit'] }}</p>

                        <a href="{{url('/cart/addOne/'.$id )}}" class="btn btn-info nodecor">+</a>

                    </div>

                    <p>Total :Rp.{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                </div>                
            @endforeach
        @endif
        </div>
    </div> --}}
    <div class="mt-1">
        <a href="{{url('/checkout' )}}" class="btn btn-primary nodecor">Checkout</a>
    </div>
</div>


{{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}


@endsection

@section('script')
<script>

</script>
@endsection