@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transaksi</h2>
    <form method="GET" action="{{ url()->current() }}">
        
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="fromDate">From Date</label>
                <input type="date" class="form-control" id="fromDate" name="fromDate">
            </div>
            <div class="col-md-3">
                <label for="toDate">To Date</label>
                <input type="date" class="form-control" id="toDate" name="toDate">
            </div>
            <div class="col-md-3">
                <label for="minAmount">Min Amount</label>
                <input type="number" class="form-control" id="minAmount" name="minAmount" placeholder="Min Amount">
            </div>
            <div class="col-md-3">
                <label for="maxAmount">Max Amount</label>
                <input type="number" class="form-control" id="maxAmount" name="maxAmount" placeholder="Max Amount">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <table class="table" id="tabelTransaksi">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Nama Pembeli</th>
                <th>Alamat Pengiriman</th>
                <th>tanggal pembelian</th>
                <th>Total</th>
                <th>Status</th>
                <th>Bukti Pembayaran</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($htransRecords)
                @foreach ($htransRecords as $k)
                <tr>
                <td>{{$k->kodeTrans}}</td>
                <td>{{$k->namaPembeli}}</td> 
                <td>{{$k->addressSnapshot}}</td>
                <td>{{ \Carbon\Carbon::parse($k->tanggalPembelian)->format('d-m-Y') }}</td> 
                <td>Rp. {{ number_format($k->totalPembelian, 2, ",", ".") }}</td>
                <td>
                    @switch($k->status)
                        @case(0)
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                            @break
                        @case(1)
                            <span class="badge bg-warning text-dark">Pesanan sedang diproses</span>
                            @break
                        @case(2)
                            <span class="badge bg-warning text-dark">Pesanan dikirim</span>
                            @break
                        @case(3)
                            <span class="badge bg-success">Pesanan Selesai</span>
                            @break
                        @case(4)
                            <span class="badge bg-danger">Pesanan dibatalkan Pembeli.</span>
                            @break
                        @case(5)
                            <span class="badge bg-danger">Pesanan dibatalkan Penjual.</span>
                            @break
                        @default
                            <span class="badge bg-secondary">Unknown</span>
                    @endswitch
                </td>
                <td>
                    @if ($k->status == 4)
                    <div class="text-center">
                        <span class="badge bg-danger">Pesanan dibatalkan oleh pembeli dengan alasan:</span>
                        <p>{{$k->alasanBatal}}</p>
                    </div>
                    @elseif ($k->status == 5)
                    <div class="text-center">
                        <span class="badge bg-danger">Pesanan dibatalkan oleh penjual dengan alasan:</span>
                        <p>{{$k->alasanBatal}}</p>
                    </div>
                    @else
                        @if ($k->status == 0)
                            <form method="POST" action="{{ route('uploadBuktiPembayaran') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="transaction_id" value="{{ $k->id }}">
                    
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">Upload Bukti Pembayaran</h5>
                                        <p class="card-text">Silakan unggah bukti pembayaran setelah melakukan pembayaran ke <strong>BRI 71810 1000 129538 Hansen Bulain</strong>.</p>
                    
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control" id="buktiPembayaran" name="buktiPembayaran" accept="image/*" required>
                                        </div>
                    
                                        <button type="submit" class="btn btn-success w-100">Upload</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="text-center">
                                <a href="#" class="openImageModal" data-bs-toggle="modal" data-bs-target="#imageModal" 
                                    data-image="{{ asset('images/bukti/' . $k->buktiPembayaran) }}" 
                                    data-title="{{ $k->kodeTrans }}">
                                    <img src="{{ asset('images/bukti/' . $k->buktiPembayaran) }}" alt="Product Image" style="width: 100px; height: auto;">
                                </a>
                            </div>
                        @endif
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-info btn-sm my-1" style="width: 120px"
                            data-id="{{ $k->id }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($k->tanggalPembelian)->format('d-m-Y') }}"
                            data-diskon="Rp{{ number_format($k->discount, 2, ',', '.') }}"
                            data-total="Rp{{ number_format($k->totalPembelian, 2, ',', '.') }}"
                            data-transaksi='@json($k->dtrans)'
                            data-bs-toggle="modal" data-bs-target="#transactionDetailModal">
                            Detail
                    </button>

                    @if ($k->status == 1 || $k->status == 0)
                    <button class="btn btn-danger btn-sm cancelbtn" style= "width: 120px;" 
                    data-bs-toggle="modal"
                    data-bs-target="#cancelConfirmationModal" 
                    data-id = "{{$k->id}}"
                    data-kode = "{{$k->kodeTrans}}">
                        Batalkan
                    </button>
                    @endif
                </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<!--Modal gambar bukti -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Product Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<!-- modal detail -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionDetailModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Tanggal Pembelian:</strong> <span id="modalTanggalPembelian"></span></p>
                <p><strong>Pemotongan Harga:</strong> <span id="modalDiskon"></span></p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Gambar Produk</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Barang</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="transactionDetailsBody">
   
                    </tbody>
                    <p><strong>Total Transaksi:</strong> <span id="modalTotalTransaksi"></span></p>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pembatalan -->
<div class="modal fade" id="cancelConfirmationModal" tabindex="-1" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelConfirmationModalLabel">Konfirmasi Pembatalan Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin membatalkan pesanan dengan Kode <strong id="showKode"></strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ url('/transaction/cancel') }}" method="POST" class="w-100" id="cancelTransactionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="inputAlasan" class="form-label">Alasan Pembatalan</label>
                        <select class="form-select @error('inputAlasan') is-invalid @enderror" id="inputAlasan" name="inputAlasan" required>
                            <option value="" disabled selected>Pilih alasan pembatalan</option>
                            <option value="Saya berubah pikiran">Saya berubah pikiran</option>
                            <option value="Ada kesalahan dalam pemesanan">Ada kesalahan dalam pemesanan</option>
                            <option value="Harga lebih murah di tempat lain">Harga lebih murah di tempat lain</option>
                        </select>
                    </div>
                    <input type="hidden" id="transactionToCancel" name="transactionID">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Ya, Batalkan Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
    $('#tabelTransaksi').dataTable({
        responsive: true,
        order: [[0, 'desc']]
    });

    // detail transaksi
    $('#transactionDetailModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');
        const tanggalPembelian = button.data('tanggal');
        const totalTransaksi = button.data('total');
        const transaksiDetails = button.data('transaksi');
        const diskon = button.data('diskon');

        const modal = $(this);
        modal.find('#modalTanggalPembelian').text(tanggalPembelian);
        modal.find('#modalTotalTransaksi').text(totalTransaksi);
        modal.find('#modalDiskon').text(diskon);
        modal.find('#transactionDetailsBody').empty();
        
        transaksiDetails.forEach(function(item) {
            const productImage = item.product.fotoPromosi 
                ? `<img src="{{ asset('images/uploads') }}/${item.product.fotoPromosi}" style="width: 100px;" alt="${item.product.namaBarang}">`
                : 'No Image';

            const row = `
                <tr>
                    <td>${productImage}</td>
                    <td>${item.product.namaBarang}</td>
                    <td>${item.totalJumlah} ${item.satuanBarang}</td>
                    <td>Rp${parseFloat(item.hargaSatuan).toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                    <td>Rp${(item.hargaSatuan * item.totalJumlah).toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                </tr>
            `;
            modal.find('#transactionDetailsBody').append(row);
        });
    });

    // modal pembatalan 
    document.querySelectorAll('.cancelbtn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const kode = this.getAttribute('data-kode');
            document.getElementById('transactionToCancel').value = id;
            document.getElementById('showKode').textContent = kode;
            document.getElementById('cancelConfirmationModalLabel').textContent = "Transaksi " + kode;
        });
    });

    // modal gambar bukti
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');

    document.querySelectorAll('.openImageModal').forEach(item => {
        item.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image');
            const imageTitle = this.getAttribute('data-title');
            
            modalImage.src = imageSrc;
            modalTitle.textContent = "Transaksi " + imageTitle;
        });
    });
});
</script>
@endsection