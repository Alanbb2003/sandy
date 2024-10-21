@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transaksi</h2>
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
                <td>{{ \Carbon\Carbon::parse($k->tanggalPembelian)->format('d-m-Y H:i:s') }}</td> 
                <td>Rp. {{ number_format($k->totalPembelian, 2, ",", ".") }}</td>
                @switch($k->status)
                    @case(1)
                        <td>Menunggu pembayaran.</td>
                        @break
                    @case(2)
                        <td>Pesanan sedang diproses.</td>
                        @break
                    @case(3)
                        <td>The order has been completed.</td>
                        @break
                    @case(4)
                        <td>Pesanan dibatalkan Pembeli.</td>
                        @break
                    @case(5)
                        <td>Pesanan dibatalkan Penjual.</td>
                        @break
                    @default
                        <td>Unknown order status.</td>
                @endswitch
                <td>
                    @if ($k->status == 4)
                    <div class="text-center">
                        <p>Pesanan dibatalkan oleh pembeli.</p>
                    </div>
                    @elseif ($k->status == 5)
                    <div class="text-center">
                        <p>Pesanan dibatalkan oleh penjual.</p>
                    </div>
                    @else
                        @if ($k->buktiPembayaran == null)
                            <form method="POST" action="{{ route('uploadBuktiPembayaran') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="transaction_id" value="{{ $k->id }}">
                    
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">Upload Bukti Pembayaran</h5>
                                        <p class="card-text">Silakan unggah bukti pembayaran Anda.</p>
                    
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control" id="buktiPembayaran" name="buktiPembayaran" accept="image/*" required>
                                        </div>
                    
                                        <button type="submit" class="btn btn-success w-100">Upload</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <!-- Show uploaded image and provide the image URL -->
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $k->buktiPembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded mb-2" style="width: 100px;">
                                <p>
                                    <a href="{{ asset('storage/' . $k->buktiPembayaran) }}" target="_blank" class="btn btn-link">Lihat Bukti Pembayaran</a>
                                </p>
                            </div>
                        @endif
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-info btn-sm my-1" style="width: 120px"
                            data-id="{{ $k->id }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($k->tanggalPembelian)->format('d-m-Y H:i:s') }}"
                            data-diskon="Rp{{ number_format($k->discount, 2, ',', '.') }}"
                            data-total="Rp{{ number_format($k->totalPembelian, 2, ',', '.') }}"
                            data-transaksi='@json($k->dtrans)'
                            data-bs-toggle="modal" data-bs-target="#transactionDetailModal">
                            Detail
                    </button>

                    @if ($k->status == 1)
                    <button class="btn btn-danger btn-sm" style="width: 120px" data-bs-toggle="modal" data-bs-target="#cancelConfirmationModal" onclick="setCancelId({{ $k->id }}, '{{ $k->kodeTrans }}')">
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
<!-- Modal for Transaction Details -->
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
                <!-- Transaction Details Table -->
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
<!-- cancel Confirmation Modal -->
<div class="modal fade" id="cancelConfirmationModal" tabindex="-1" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelConfirmationModalLabel">Confirm cancelation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Yakin membatalkan transaksi?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
     
                <form id="cancelTransactionForm" method="POST" action="{{ url('/transaction/cancel') }}">
                    @csrf
                    <input type="hidden" id="transactionToCancel" name="transactionID">
                    <button type="submit" class="btn btn-danger">Yakin</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#tabelTransaksi').dataTable({
            responsive: true,
            order: [[0, 'desc']] // Order by the 4th column (Tanggal Pembelian)
        });

        $('#transactionDetailModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); 

          // Extract data from attributes
          var id = button.data('id');
          var tanggalPembelian = button.data('tanggal');
          var totalTransaksi = button.data('total');
          var transaksiDetails = button.data('transaksi');
          var diskon = button.data('diskon');

          var modal = $(this);
          modal.find('#modalTanggalPembelian').text(tanggalPembelian);
          modal.find('#modalTotalTransaksi').text(totalTransaksi);
          modal.find('#modalDiskon').text(diskon);
          modal.find('#transactionDetailsBody').empty();
          
          transaksiDetails.forEach(function(item) {
              var productImage = item.product.fotoPromosi ? `<img src="{{ asset('images/uploads') }}/${item.product.fotoPromosi}" style="width: 100px;" alt="${item.product.namaBarang}">` : 'No Image';
              
              var row = `
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
    });

    function setCancelId(id,kode) {
        document.getElementById('transactionToCancel').value = id;
        document.getElementById('cancelConfirmationModalLabel').textContent = "Transaksi " + kode;
    }

    // function confirmCancel(orderId) {
    //     if (confirm("Are you sure you want to cancel this order?")) {

    //         window.location.href = "/cancel-order/" + orderId;
    //     }
    // }

    
</script>
@endsection