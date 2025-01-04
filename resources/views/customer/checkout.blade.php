@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-6 col-md-12 mb-4">
            <h2>Checkout</h2>
            <hr>
            <form action="{{ url('/checkout') }}" method="POST">
                @csrf

                <!-- Address Selection -->
                <div class="mb-3">
                    <label for="inputAddress" class="form-label">Pilih Alamat</label>
                    <select name="inputAddress" id="inputAddress" 
                        class="form-select @error('inputAddress') is-invalid @enderror" 
                        aria-label="Select Address">
                        <option value="" disabled selected>-- Pilih Alamat --</option>
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
                    @error('inputAddress')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <a href="{{ url('/address') }}">Tambah Alamat</a>
                </div>

                <hr>

                <!-- Promotions and Points -->
                <div class="mb-4">
                    <h3>Biaya dan Promosi</h3>
                    <div class="mb-3">
                        <h6>Poin Saya</h6>
                        <p>Total Poin: {{ $currentPoints }}</p>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="usePoin" name="usePoin" 
                                @if (!$memberstatus || $currentPoints < 1000) disabled @endif 
                                data-toggle="tooltip" 
                                title="@if (!$memberstatus) Anda harus memiliki keanggotaan. @elseif ($currentPoints < 1000) Anda harus memiliki minimal 1000 poin. @endif"
                                onchange="updateTotalAmount()">
                            <label class="form-check-label" for="usePoin">Gunakan Poin</label>
                        </div>
                    </div>
                    @if ($memberstatus)
                        <strong>Poin yang didapat dari transaksi ini:</strong>
                        <p>{{ $pointsEarned }}</p>
                    @endif
                </div>

                <!-- Payment and Submit -->
                <div class="mb-4">
                    <p>Pembayaran dilakukan ke <strong>BRI 71810 1000 129538 Hansen Bulain</strong></p>
                    <button type="submit" class="btn btn-primary w-100">Buat Pesanan</button>
                </div>
            </form>
        </div>

        <!-- Summary Section -->
        <div class="col-lg-6 col-md-12">
            <div>
                <h4>Ringkasan</h4>
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Total:</h5>
                    <h5 id="totalAmountDisplay">Rp.{{ number_format($totalAmmount, 2, ",", ".") }}</h5>
                    <h5 id="totalAfterPointsDisplay" style="display: none; margin-left: 5px;">
                        Rp.{{ number_format($totalAmmount, 2, ",", ".") }}
                    </h5>
                </div>

                <!-- Cart Items -->
                <div class="mt-3" style="max-height: 300px; overflow-y: auto;overflow-x: hidden;">
                    @if(session('cart'))
                        @foreach(session('cart') as $id => $details)
                            <div class="row align-items-center mb-3">
                                <div class="col-3">
                                    <img src="{{ asset('images/uploads/' . $details['image']) }}" 
                                        class="img-fluid rounded" 
                                        alt="{{ $details['name'] }}">
                                </div>
                                <div class="col-9">
                                    <p class="mb-1"><strong>{{ $details['name'] }}</strong></p>
                                    <p class="mb-1">Harga: Rp.{{number_format( $details['price']), 2,",","." }}</p>
                                    <p class="mb-1">QTY: {{$details['quantity']}} {{ $details['unit'] }}</p>
                                    <p class="mb-1">Subtotal: Rp.{{ number_format($details['price'] * $details['quantity'], 2, ",", ".") }}</p>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @else
                        <p>Keranjang kosong.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    function updateTotalAmount() {
        const usePointsCheckbox = document.getElementById('usePoin');
        const originalTotal = {{ $totalAmmount }};
        const discount = {{ $currentPoints >= 1000 ? $currentPoints : 0 }};
        let newTotal = originalTotal;

        if (usePointsCheckbox.checked) {
            newTotal = originalTotal - discount;

            document.getElementById('totalAmountDisplay').style.textDecoration  = 'line-through';
            document.getElementById('totalAmountDisplay').innerText = `Rp.${originalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
        
            document.getElementById('totalAfterPointsDisplay').innerText = `  Rp.${newTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
            document.getElementById('totalAfterPointsDisplay').style.display = 'block';
        } else {
            document.getElementById('totalAmountDisplay').style.textDecoration = 'none';
            document.getElementById('totalAfterPointsDisplay').style.display = 'none';
        }
    }
</script>
@endsection