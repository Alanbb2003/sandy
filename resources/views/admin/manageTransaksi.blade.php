@extends('layouts.appAdmin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
      <div class="card-header"><b>Manage Barang</b></div>
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
              <td>{{ $htrans->id }}</td>
    
              <!-- Display the user's first and last name -->
              <td>{{ $htrans->user->firstName }} {{ $htrans->user->lastName }}</td>
          
              <td>{{ $htrans->tanggalPembelian }}</td> 

              <td>
                <button type="button" class="btn btn-info" 
                    data-id="{{ $htrans->id }}"
                    data-nama="{{ $htrans->user->firstName }} {{ $htrans->user->lastName }}"
                    data-tanggal="{{ $htrans->tanggalPembelian }}"
                    data-total="Rp{{ number_format($htrans->totalPembelian, 2, ',', '.') }}"
                    data-transaksi='@json($htrans->dtrans)'
                    data-bs-toggle="modal" data-bs-target="#detailModal">
                    Detail
                </button>
              </td>

              <td>Rp{{ number_format($htrans->totalPembelian, 2, ',', '.') }}</td>
              
              <td>
                  @if ($htrans->buktiPembayaran)
                      <div class="text-center">
                            <img src="{{ asset('storage/' . $htrans->buktiPembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded mb-2" style="width: 100px;">
                            <p>
                                <a href="{{ asset('storage/' . $htrans->buktiPembayaran) }}" target="_blank" class="btn btn-link">Lihat Bukti Pembayaran</a>
                            </p>
                        </div>
                  @else
                      No proof
                  @endif
              </td>
              @switch($htrans->status)
                  @case(1)
                      <td>Menunggu pembayaran.</td>
                      @break
                  @case(2)
                      <td>The order is being processed.</td>
                      @break
                  @case(3)
                      <td>The order has been completed.</td>
                      @break
                  @case(4)
                      <td>Pesanan dibatalkan.</td>
                      @break
                  @default
                      <td>Unknown order status.</td>
              @endswitch
              <td>
                  <a href="#" class="btn btn-info btn-sm my-1" style="width: 120px" 
                     data-bs-toggle="modal" 
                     data-bs-target="#acceptTransactionModal" 
                     data-id="{{ $htrans->id }}">
                    <i class="fa fa-check"></i>
                  </a>

                    @if ($htrans->status == 1)
                    <a href="#" class="btn btn-danger btn-sm" style="width: 120px">
                      <i class="fa fa-xmark"></i>
                    </a>
                    @endif
              </td>
            @endforeach
            </tbody>
          </table>
      </div>
    </div>

    <!-- Accept Confirmation Modal -->
    <div class="modal fade" id="acceptTransactionModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="acceptModalLabel">Confirm Transaction Acceptance</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to accept this transaction?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            
            <!-- Form to trigger accept transaction -->
            <form id="acceptTransactionForm" method="POST" action="{{ route('admin.acceptTransaction') }}">
              @csrf
              <input type="hidden" name="transaction_id" id="transactionId">
              <button type="submit" class="btn btn-primary">Yes, Accept</button>
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
                    <h5>Nama Pembeli: <span id="modalNamaPembeli"></span></h5>
                    <p>Tanggal Pembelian: <span id="modalTanggalPembelian"></span></p>
                    <h6>Transaction Details:</h6>
    
                    <!-- Table to display transaction details -->
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
                            <!-- Dynamic Content -->
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
    $(document).ready(function(){
        $('#tabelTransaksi').dataTable({
          responsive: true
        });

        $('#detailModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Button that triggered the modal
          
          // Extract data from attributes
          var id = button.data('id');
          var namaPembeli = button.data('nama');
          var tanggalPembelian = button.data('tanggal');
          var totalTransaksi = button.data('total');
          var transaksiDetails = button.data('transaksi');
          
          // Set modal title and fields
          var modal = $(this);
          modal.find('#modalNamaPembeli').text(namaPembeli);
          modal.find('#modalTanggalPembelian').text(tanggalPembelian);
          modal.find('#modalTotalTransaksi').text(totalTransaksi);
          
          // Clear existing table content
          modal.find('#modalTransaksiDetails').empty();
          
          // Populate transaction details table
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
              modal.find('#modalTransaksiDetails').append(row);
          });
      });

      
    });
    document.addEventListener('DOMContentLoaded', function () {
          var acceptModal = document.getElementById('acceptTransactionModal');

          acceptModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;

            // Extract info from data-* attributes
            var transactionId = button.getAttribute('data-id');

            // Update the form's hidden input value with the transaction ID
            var inputTransactionId = document.getElementById('transactionId');
            inputTransactionId.value = transactionId;
          });
        });
    
</script>
@endsection