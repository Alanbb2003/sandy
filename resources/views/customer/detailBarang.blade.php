@extends('layouts.app')

@section('content')
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
            <h3>{{ $barang->namaBarang }}</h3>
            <p>{{ $barang->deskripsi }}</p>
            <p>Price: Rp.{{ $barang->hargaKecil }} per {{ $barang->totalQuantity }} {{$barang->satuanTerkecil}}</p>
            @if($barang->satuanBesar && $barang->hargaBesar)
            <p>Price: Rp.{{ $barang->hargaBesar }} per  {{$barang->totalQuantity/$barang->isiSatuanBesar}} {{$barang->satuanBesar}}</p>
            @endif
            
            
            

            <div>
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
                {{-- <label for="quantity_{{ $barang->id }}">Quantity:</label>
                <input type="number" id="quantity_{{ $barang->id }}" name="quantity" min="1" value="1" class="form-control">
                
                <label for="unit_{{ $barang->id }}">Unit:</label>
                <select id="unit_{{ $barang->id }}" name="unit" class="form-control">
                    <option value="small">{{ $barang->satuanTerkecil }}</option>
                    @if($barang->satuanBesar)
                    <option value="big">{{ $barang->satuanBesar }}</option>
                    @endif
                </select>

                <label for="total">Total:</label>
                <input type="number" id="total" name="total"  class="form-control">
                 --}}
            </div>
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
            </div>
            

            {{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}
        </div>
    </div>
</div>


@endsection

@section('script')
<script>

</script>
@endsection