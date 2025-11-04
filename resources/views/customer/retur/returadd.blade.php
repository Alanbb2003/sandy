@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5>Retur Transaksi - {{ $transaction->kodeTrans }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('retur.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kodeTrans" value="{{ $transaction->kodeTrans }}">

                <div class="mb-4">
                    <p><strong>Tanggal Pembelian:</strong> {{ \Carbon\Carbon::parse($transaction->tanggalPembelian)->format('d/m/Y') }}</p>
                    <p><strong>Total Pembelian:</strong> Rp{{ number_format($transaction->totalPembelian) }}</p>
                </div>

                <div class="mb-4">
                    <h6>Tipe Pengembalian:</h6>
                    <div class="form-group">
                        <select name="TipePengembalian" class="form-control" required>
                            <option value="">-- Pilih Tipe Pengembalian --</option>
                            <option value="Pengembalian dana">Pengembalian dana</option>
                            <option value="Penukaran barang">Penukaran barang</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <h6>Barang yang Dibeli:</h6>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Dibeli</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah Retur</th>
                                <th>Alasan Retur</th>
                                <th>Upload Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dtransItems as $item)
                            <tr>
                                <td>{{ $item->product->namaBarang }}</td>
                                <td>{{ $item->totalJumlah }} {{ $item->satuanBarang }}</td>
                                <td>Rp{{ number_format($item->hargaSatuan) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input 
                                            type="checkbox" 
                                            name="items[{{ $item->id }}][include]" 
                                            value="1" 
                                            class="form-check-input me-2 include-checkbox"
                                            onchange="toggleFields(this, {{ $item->id }})">
                                        <input 
                                            type="number" 
                                            name="items[{{ $item->id }}][quantity]" 
                                            class="form-control quantity-input item-{{ $item->id }}" 
                                            min="0" 
                                            max="{{ $item->totalJumlah }}" 
                                            value="0" 
                                            data-max="{{ $item->totalJumlah }}" 
                                            disabled>
                                    </div>
                                </td>
                                <td>
                                    <textarea 
                                        name="items[{{ $item->id }}][reason]" 
                                        class="form-control item-{{ $item->id }}" 
                                        rows="2"
                                        disabled></textarea>
                                </td>
                                <td>
                                    <input 
                                        type="file" 
                                        name="items[{{ $item->id }}][image]" 
                                        class="form-control item-{{ $item->id }}" 
                                        disabled>
                                    <input type="hidden" name="items[{{ $item->id }}][satuan]" value="{{ $item->satuanBarang }}">
                                    <input type="hidden" name="items[{{ $item->id }}][price]" value="{{ $item->hargaSatuan }}">
                                    <input type="hidden" name="items[{{ $item->id }}][namaBarang]" value="{{ $item->product->namaBarang }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Submit Retur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function toggleFields(checkbox, itemId) {
        const fields = document.querySelectorAll(`.item-${itemId}`);
        fields.forEach(field => {
            field.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                field.value = ''; // Clear the input values if unchecked
            }
        });
    }

    // Optionally, handle form submission to revalidate quantities
    document.querySelector('form').addEventListener('submit', function (event) {
        const inputs = document.querySelectorAll('.quantity-input');
        let valid = true;

        inputs.forEach(input => {
            const max = parseInt(input.dataset.max, 10);
            const min = 0;
            const value = parseInt(input.value, 10);

            if (value > max || value < min) {
                valid = false;
                input.value = Math.min(Math.max(value, min), max);
            }
        });

        if (!valid) {
            alert('Please correct invalid quantities before submitting.');
            event.preventDefault();
        }
    });
</script>
@endsection