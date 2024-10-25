{{-- @extends('layouts.app') --}}
@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
          <div class="card-header"> <b>Manage Barang</b> </div>
          <div class="card-body">
              <table class="table table-striped table-bordered" id="tablePelanggan" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Total Transaksi Selesai</th>
                        <th>Total Jumlah Transaksi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customers as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->firstName }} {{ $c->lastName }}</td>
                        <td>{{ $c->noHp }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->tanggalLahir ? \Carbon\Carbon::parse($c->tanggalLahir)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $c->total_completed_transactions }}</td>
                        <td>{{ number_format($c->total_transaction_amount, 2) }}</td>
                        <td>
                          <button class="btn btn-sm btn-primary detail-btn" 
                            data-customer="{{ json_encode($c) }}"
                            data-bs-toggle="modal" 
                            data-bs-target="#customerDetailModal">
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
          </div>
        </div>
    </div>
</div>


{{-- <!-- Single Modal for Customer Details -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="customerDetailModalLabel">Customer Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p><strong>Email:</strong> <span id="modal-email"></span></p>
              <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
              <p><strong>Birthdate:</strong> <span id="modal-birthdate"></span></p>

              <h4>Wishlist</h4>
              <ul id="modal-wishlist"></ul>

              <h4>Most Bought Category</h4>
              <p id="modal-most-bought-category"></p>
          </div>
      </div>
  </div>
</div> --}}


<!-- Single Modal for Customer Details -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="customerDetailModalLabel">Customer Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p><strong>Email:</strong> <span id="modal-email"></span></p>
              <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
              <p><strong>Birthdate:</strong> <span id="modal-birthdate"></span></p>

              <h4>Wishlist</h4>
              <table class="table table-bordered">
                  <thead>
                      <tr>
                          <th>Product Name</th>
                          <th>Price</th>
                      </tr>
                  </thead>
                  <tbody id="modal-wishlist">
                      <!-- Wishlist items will be injected here -->
                  </tbody>
              </table>

              <h4>Past Transactions</h4>
              <table class="table table-bordered">
                  <thead>
                      <tr>
                          <th>Kode Transaksi</th>
                          <th>Date</th>
                          <th>Total Amount</th>
                          <th>Status</th>
                          <th>Action</th> <!-- New column for the action button -->
                      </tr>
                  </thead>
                  <tbody id="modal-transactions">
                      <!-- Transaction items will be injected here -->
                  </tbody>
              </table>

              <!-- Section to display dtrans details -->
              <div id="dtrans-details" class="mt-3" style="display:none;">
                  <h5>Transaction Details</h5>
                  <table class="table table-bordered" id="dtrans-table">
                      <thead>
                          <tr>
                              <th>Product Name</th>
                              <th>Quantity</th>
                              <th>Price</th>
                              <th>Subtotal</th>
                          </tr>
                      </thead>
                      <tbody id="modal-dtrans">
                          <!-- dtrans items will be injected here -->
                      </tbody>
                  </table>
              </div>

              <h4>Most Bought Category</h4>
              <p id="modal-most-bought-category"></p>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){
        $('#tablePelanggan').dataTable({
          responsive: true
        } );
    });

    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.detail-btn');
        
        detailButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Parse customer data from the button's data attribute
                const customer = JSON.parse(this.getAttribute('data-customer'));
                
                // Populate modal fields
                document.getElementById('modal-email').textContent = customer.email;
                document.getElementById('modal-phone').textContent = customer.noHp;
                document.getElementById('modal-birthdate').textContent = customer.tanggalLahir ? customer.tanggalLahir : '-';

                // Populate wishlist table
                const wishlistContainer = document.getElementById('modal-wishlist');
                wishlistContainer.innerHTML = ''; // Clear previous items
                customer.wishlists.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.product ? item.product.namaBarang : 'Product Not Found'}</td>
                        <td>${item.product ? item.product.hargaKecil : '-'}</td>
                    `;
                    wishlistContainer.appendChild(row);
                });

               // Populate transactions table
              const transactionsContainer = document.getElementById('modal-transactions');
              transactionsContainer.innerHTML = ''; 
              customer.htrans.forEach(transaction => {
                  const statusClass = (() => {
                      switch (transaction.status) {
                          case 1:
                          case 2:
                              return 'badge bg-warning text-dark'; // Menunggu Pembayaran / Pesanan Sedang Diproses
                          case 3:
                              return 'badge bg-success'; // Transaksi Berhasil
                          case 4:
                          case 5:
                              return 'badge bg-danger'; // Dibatalkan Pembeli / Dibatalkan Penjual
                          default:
                              return 'badge bg-secondary'; // Status Tidak Diketahui
                      }
                  })();

                  const statusText = (() => {
                      switch (transaction.status) {
                          case 1:
                              return 'Menunggu Pembayaran';
                          case 2:
                              return 'Pesanan Sedang Diproses';
                          case 3:
                              return 'Transaksi Berhasil';
                          case 4:
                              return 'Pesanan Dibatalkan Pembeli';
                          case 5:
                              return 'Pesanan Dibatalkan Penjual';
                          default:
                              return 'Status Tidak Diketahui';
                      }
                  })();

                  const row = document.createElement('tr');
                  row.innerHTML = `
                      <td>${transaction.kodeTrans}</td>
                      <td>${new Date(transaction.tanggalPembelian).toLocaleDateString('id-ID')}</td>
                      <td>Rp. ${transaction.totalPembelian.toLocaleString('id-ID')}</td>
                      <td><span class="${statusClass}">${statusText}</span></td>
                      <td>
                          <button class="btn btn-sm btn-info" onclick="toggleDtrans('${transaction.kodeTrans}')">View Details</button>
                      </td>
                  `;
                  transactionsContainer.appendChild(row);
              });

              // Set most bought category if available
              document.getElementById('modal-most-bought-category').textContent = customer.most_bought_category 
                  ? customer.most_bought_category.nama_category 
                  : 'No transactions';

              // Function to toggle and show dtrans details
              window.toggleDtrans = function(kodeTrans) {
                  const dtransContainer = document.getElementById('dtrans-details');
                  const dtransTableBody = document.getElementById('modal-dtrans');
                
                  dtransTableBody.innerHTML = '';

                  // Find the transaction by kodeTrans
                  const transaction = customer.htrans.find(t => t.kodeTrans === kodeTrans);
                  
                  if (transaction && transaction.dtrans) {
                      transaction.dtrans.forEach(detail => {
                          const dtransRow = document.createElement('tr');
                          dtransRow.innerHTML = `
                              <td>${detail.product ? detail.product.namaBarang : 'Product Not Found'}</td>
                              <td>${detail.totalJumlah} ${detail.satuanBarang}</td>
                              <td>Rp. ${detail.hargaSatuan.toLocaleString('id-ID')}</td>
                              <td>Rp. ${detail.subtotal.toLocaleString('id-ID')}</td>
                          `;
                          dtransTableBody.appendChild(dtransRow);
                      });
                  }

                  // Show or hide dtrans details
                  dtransContainer.style.display = dtransTableBody.innerHTML ? 'block' : 'none';
              };
                
                document.getElementById('modal-most-bought-category').textContent = customer.most_bought_category 
                    ? customer.most_bought_category.nama_category 
                    : 'No transactions';
            });
        });
    });
</script>
@endsection