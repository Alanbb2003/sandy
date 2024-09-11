@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h2>Checkout</h2>

            <hr>
                <form action="" method="POST">
                    @csrf
                    <div>
                        <div>
                            <select name="adress" id="adress">
                                @if ($address->isEmpty())
                                    <option value="none">Belum ada alamat</option>
                                @else
                                    @foreach ($address as $a)
                                        <option value="{{$a->id}}">
                                            <div class="col">
                                                <div class="row">{{$a->namaDepan}} {{$a->namaBelakang}}</div>
                                                <div class="row">{{$a->noHP}}</div>
                                                <div class="row">{{$a->detailAlamat}}</div>
                                            </div>
                                            
                                        </option>
                                    @endforeach
                                @endif
                              </select>
                        </div>
                        <div>
                            <a href="{{url('/address')}}">Tambah Alamat</a>
                        </div>
                    </div>
        
                    <hr>
                    
                    <div>
                        <h3>Biaya dan promosi</h3>
                        <h5>Pilih Promo</h5>
                        <div class="row">
                            <h6>Poin Saya</h6>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="usePoin" name="usePoin">
                                <label class="form-check-label" for="usePoin">Gunakan Poin</label>
                            </div>                            
                        </div>
                    </div>

                </form>
        </div>
        <div class="col">
            <div>
                <h4>ringkasan</h4>
                <div>
                @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                    <div class="row">
                        <div class="col">
                            <img src="{{ asset('images/uploads/'.$details['image'] )}}" width="50" height="50" class="thumbnailBig" />
                        </div>
                        
                        <div class="col">
                            <p>{{ $details['name'] }}</p>
                            <div class="row justify-content-center">
    
                                <p>QTY :{{ $details['quantity'] }}  {{ $details['unit'] }}</p>
    
                            </div>
        
                            <p>Total :Rp. {{number_format($details['price'] * $details['quantity'],2,",",".")}}</p>
                        </div>      
                    </div>    
                    <hr>      
                    @endforeach
                @endif
                </div>
            </div>
        </div>
    </div>
    
</div>


{{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}


@endsection

@section('script')
<script>

</script>
@endsection