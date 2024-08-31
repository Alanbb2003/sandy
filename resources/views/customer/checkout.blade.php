@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Checkout</h1>

            <hr>

            <div>
                <div>
                    alamat disini
                </div>
                <div>
                    <a href=""><button>Tambah Alamat</button></a>
                    <a href=""><button>Ubah Alamat</button></a>
                </div>
            </div>

            <hr>
            
            <div>
                <h3>Biaya dan promosi</h3>
                <h5>Pilih Promo</h5>
                <div class="row">
                    <h6>Poin Saya</h6>
                    <button>Gunakan Poin</button>
                </div>
            </div>

        </div>
        <div class="col">
            <div>
                <h4>ringkasan</h4>
                <div>
                @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                    
                        <div class="col">
                            <img src="{{ asset('images/uploads/'.$details['image'] )}}" width="50" height="50" class="thumbnailBig" />
                        </div>
                        
                        <div class="col">
                            <p>{{ $details['name'] }}</p>
                            <div class="row justify-content-center">
    
                                <p>QTY :{{ $details['quantity'] }}  {{ $details['unit'] }}</p>
    
                            </div>
        
                            <p>Total :Rp. {{number_format($details['price'] * $details['quantity'],2,",",".")}} {{ $details['price'] * $details['quantity'] }}</p>
                        </div>                
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