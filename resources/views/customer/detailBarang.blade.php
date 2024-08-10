@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col">
        <div class="row">
            <img class="thumbnail" src="{{asset('images/uploads/'.$barang->fotoPromosi)}}" alt="Gambar Barang">
            @foreach ($pic as $p)
                <img class="thumbnail" src="{{asset('images/uploads/'.$p->fileName)}}" alt="Gambar Barang">
            @endforeach

            <h3>{{ $barang->namaBarang }}</h3>
            <p>{{ $barang->deskripsi }}</p>
            <p>Price: ${{ $barang->hargaKecil }} per {{ $barang->totalQuantity }} {{$barang->satuanTerkecil}}</p>
            @if($barang->satuanBesar && $barang->hargaBesar)
            <p>Price: ${{ $barang->hargaBesar }} per  {{$barang->totalQuantity/$barang->isiSatuanBesar}} {{$barang->satuanBesar}}</p>
            @endif

            <div>
                <label for="quantity_{{ $barang->id }}">Quantity:</label>
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
                
            </div>
            
            <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button>
        </div>

        <div class="row">

        </div>
    </div>
  
</div>


@endsection

@section('script')
<script>

</script>
@endsection