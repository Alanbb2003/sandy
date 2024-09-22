@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h2>Checkout</h2>

            <hr>
                <form action="{{url('/checkout')}}" method="POST">
                    @csrf
                    <div>
                        <div>
                            <select name="inputAddress" id="address">
                                @if ($address->isEmpty())
                                    <option value="none">Belum ada alamat</option>
                                @else
                                    @foreach ($address as $a)
                                    <option value="{{ $a->id }}">
                                        {{ $a->namaDepan }} {{ $a->namaBelakang }}, {{ $a->noHP }}, {{ $a->detailAlamat }}, {{ $a->kota }}, {{ $a->provinsi }}, {{ $a->kodePos }}
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
                        @if (count($memberstatus) === 0)
                        <h1>member null</h1>
                        @else
                        <h1>{{$memberstatus}}</h1>
                        @endif
                        
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
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
                <!-- Address Form -->
        {{-- <form action="{{url('/checkout')}}" method="POST">
            @csrf
            <div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
                        Pilih Alamat
                    </button>
                </div>
                <div>
                    <a href="{{url('/address')}}">Tambah Alamat</a>
                </div>
            </div>

            <div id="selectedAddress" class="mt-3">
                <!-- Display selected address here -->
                Belum ada alamat terpilih.
            </div>

            <input type="hidden" name="inputAddress" id="inputAddress">

            <hr>

            <div>
                @if (count($memberstatus) === 0)
                    <h1>member null</h1>
                @else
                    <h1>{{$memberstatus}}</h1>
                @endif

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
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form> --}}
        </div>
        <div class="col">
            <div>
                <h4>ringkasan</h4>
                <h3>Total : Rp.{{number_format($totalAmmount,2,",",".")}}</h3>
                <div>
                @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                    <div class="row">
                        <div class="col">
                            <img src="{{ asset('images/uploads/'.$details['image'] )}}" width="50" height="50" class="thumbnailSmall" />
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

<!-- Address Selection Modal -->
{{-- <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Pilih Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($address->isEmpty())
                    <p>Belum ada alamat</p>
                @else
                    @foreach ($address as $a)
                        <div class="address-item" data-id="{{ $a->id }}" 
                             data-full-address="{{ $a->namaDepan }} {{ $a->namaBelakang }}, {{ $a->noHP }}, {{ $a->detailAlamat }}, {{ $a->kota }}, {{ $a->provinsi }}, {{ $a->kodePos }}">
                            <div class="border p-3 mb-3">
                                <strong>{{ $a->namaDepan }} {{ $a->namaBelakang }}</strong><br>
                                {{ $a->noHP }}<br>
                                {{ $a->detailAlamat }}, {{ $a->kota }}, {{ $a->provinsi }}, {{ $a->kodePos }}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}
{{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}


@endsection

@section('script')
<script>
// // Script to handle address selection
// document.addEventListener('DOMContentLoaded', function() {
//     // Get all address items in the modal
//     let addressItems = document.querySelectorAll('.address-item');
    
//     // Add event listener to each address item
//     addressItems.forEach(function(item) {
//         item.addEventListener('click', function() {
//             // Get address details from data attributes
//             let addressId = this.getAttribute('data-id');
//             let fullAddress = this.getAttribute('data-full-address');
            
//             // Set the selected address in the hidden input field
//             document.getElementById('inputAddress').value = addressId;
            
//             // Display the selected address in the main form
//             document.getElementById('selectedAddress').innerHTML = fullAddress;
            
//             // Close the modal
//             let addressModal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
//             addressModal.hide();
//         });
//     });
// });
</script>
@endsection