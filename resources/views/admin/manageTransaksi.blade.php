@extends('layouts.appAdmin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
      <div class="card-header"><h4>Manage Transaksi</h4></div>
      <div class="card-body">
          <table class="display responsive nowrap" id="tabelTransaksi" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama Pembeli</th>
                <th>Tanggal Pembelian</th>
                <th>Detail</th>
                <th>Total Transaksi</th>
                <th>Bukti</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($transaksi as $htrans)
            <tr>
              <td>{{ $htrans->kodeTrans }}</td>
              <td>{{ $htrans->user->firstName }} {{ $htrans->user->lastName }}</td>
              <td>{{ \Carbon\Carbon::parse($htrans->tanggalPembelian)->format('d-m-Y H:i:s') }}</td> 
              <td>
                <button type="button" class="btn btn-info" 
                    data-id="{{ $htrans->id }}"
                    data-nama="{{ $htrans->user->firstName }} {{ $htrans->user->lastName }}"
                    data-tanggal="{{ \Carbon\Carbon::parse($htrans->tanggalPembelian)->format('d-m-Y H:i:s') }}"
                    data-diskon="Rp{{ number_format($htrans->discount, 2, ',', '.') }}"
                    data-total="Rp{{ number_format($htrans->totalPembelian, 2, ',', '.') }}"
                    data-alamat="{{$htrans->addressSnapshot}}"
                    data-transaksi='@json($htrans->dtrans)'
                    data-bs-toggle="modal" data-bs-target="#detailModal">
                    Detail
                </button>
              </td>
              <td>Rp{{ number_format($htrans->totalPembelian, 2, ',', '.') }}</td>
              <td>
                  @if($htrans->buktiPembayaran)
                   <a href="#" class="openImageModal" data-bs-toggle="modal" data-bs-target="#imageModal" 
                        data-image="{{ asset('images/bukti/' . $htrans->buktiPembayaran) }}" 
                        data-title="{{ $htrans->kodeTrans }}">
                        <img src="{{ asset('images/bukti/' . $htrans->buktiPembayaran) }}" alt="Product Image" style="width: 100px; height: auto;">
                    </a>
                  @else
                      Belum ada
                  @endif
              </td>
              <td>
                @switch($htrans->status)
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
                  @if ($htrans->status == 1 || $htrans->status == 2)
                  <a href="#" class="btn btn-info btn-sm my-1 acceptTrans" style="width: 120px" 
                      data-bs-toggle="modal" 
                      data-bs-target="#acceptTransactionModal" 
                      data-id="{{ $htrans->id }}"
                      data-kode="{{$htrans->kodeTrans}}">
                      @if($htrans->status == 1)
                      <i class="fa fa-check"></i> Kirim
                        @elseif($htrans->status == 2)
                            <i class="fa fa-check"></i> Selesaikan
                        @endif
                  </a>
                  @endif

                  @if ($htrans->status == 0)
                  <a href="#" class="btn btn-danger btn-sm my-1 denyTrans" style="width: 120px" 
                      data-bs-toggle="modal" 
                      data-bs-target="#cancelOrderModal" 
                      data-id="{{ $htrans->id }}" 
                      data-kode="{{$htrans->kodeTrans}}">
                      <i class="fa fa-xmark"></i> Batal
                  </a>
                  @endif
              </td>
            @endforeach
            </tbody>
          </table>
      </div>
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
    <!-- Modal pembatalan -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="cancelOrderModalLabel">Konfirmasi Pembatalan Pesanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  Apakah Anda yakin ingin membatalkan pesanan dengan Kode <strong id="showKode"></strong>?
              </div>
              <div class="modal-footer">
                  <form action="{{ route('admin.cancelOrder') }}" method="POST" class="w-100">
                      @csrf
                      <div class="mb-3">
                          <label for="inputAlasan" class="form-label">Alasan Pembatalan</label>
                          <textarea class="form-control @error('inputAlasan') is-invalid @enderror" id="inputAlasan" name="inputAlasan" rows="5" placeholder="Enter your text here..." required></textarea>
                      </div>
                      <input type="hidden" name="transaction_idcancel" id="transactionIdcancel">
                      <div class="d-flex justify-content-end">
                          <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                          <button type="submit" class="btn btn-danger">Ya, Batalkan Pesanan</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>

    <!-- Modal konfirmasi -->
    <div class="modal fade" id="acceptTransactionModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="acceptModalLabel">Konfirmasi penerimaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            yakin menerima transaksi <strong id="kodetransaksiShow"></strong>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            
            <!-- Form to trigger accept transaction -->
            <form id="acceptTransactionForm" method="POST" action="{{ route('admin.acceptTransaction') }}">
              @csrf
              <input type="hidden" name="transaction_id" id="transactionId">
              <button type="submit" class="btn btn-primary">Yakin</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    {{-- modal detail --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Nama Pembeli: <span id="modalNamaPembeli"></span></h4>
                    <strong>Alamat Pengiriman: <span id="modalAlamatPembelian"></span></strong>
                    <p>Tanggal Pembelian: <span id="modalTanggalPembelian"></span></p>
                    <strong>Diskon: <span id="modalDiskon"></span></strong>
                    <h6>Transaction Details:</h6>
    
                    <table class="table table-striped" id="transactionTable">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalTransaksiDetails">

                        </tbody>
                    </table>
    
                    <p><strong>Total Transaksi:</strong> <span id="modalTotalTransaksi"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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

    $('#detailModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);

        const id = button.data('id');
        const namaPembeli = button.data('nama');
        const tanggalPembelian = button.data('tanggal');
        const totalTransaksi = button.data('total');
        const transaksiDetails = button.data('transaksi');
        const alamat = button.data('alamat');
        const diskon = button.data('diskon');
        const modal = $(this);
        modal.find('#modalNamaPembeli').text(namaPembeli);
        modal.find('#modalAlamatPembelian').text(alamat);
        modal.find('#modalTanggalPembelian').text(tanggalPembelian);
        modal.find('#modalTotalTransaksi').text(totalTransaksi);
        modal.find('#modalDiskon').text(diskon);
        modal.find('#modalTransaksiDetails').empty();
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
            modal.find('#modalTransaksiDetails').append(row);
        });
    });

    document.querySelectorAll('.acceptTrans').forEach(button=>{
        button.addEventListener('click',function(){
            const transactionId = button.getAttribute('data-id');
            const transactionKode = button.getAttribute('data-kode');

            document.getElementById('transactionId').value = transactionId;
            document.getElementById('kodetransaksiShow').textContent = transactionKode;
        })
    });

    document.querySelectorAll('.denyTrans').forEach(button=>{
        button.addEventListener('click',function(){
            const transactionId = button.getAttribute('data-id');
            const transactionKode = button.getAttribute('data-kode');

            document.getElementById('transactionIdcancel').value = transactionId;
            document.getElementById('showKode').textContent = transactionKode;
        })
    });

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