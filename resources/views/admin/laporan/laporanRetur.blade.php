@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
<h2 class="text-center">Laporan Retur</h2>
    <p class="text-center">Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ url()->current() }}" class="p-3">
                        <div class="row-md-3">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="row-md-3">
                            <label for="end_date">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Diterima</option>
                                <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <!-- Filter Button -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ url()->current() }}" class="btn btn-secondary mx-1">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fa-solid fa-filter"></i>    
    </button>

    <div class="card container px-2 py-2">
        <table class="table table-bordered table-sm compact-table" id="tableRetur">
            <thead>
                <tr>
                    <th>ID Retur</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pemohon</th>
                    <th>Tanggal Retur</th>
                    <th>Jumlah Barang</th>
                    <th>Tipe Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returs as $retur)
                    <tr>
                        <td>{{ $retur->HReturID }}</td>
                        <td>{{ $retur->htrans->kodeTrans ?? 'N/A' }}</td>
                        <td>{{ $retur->user->firstName }} {{ $retur->user->lastName }}</td>
                        <td>{{ \Carbon\Carbon::parse($retur->TanggalRetur)->format('d-m-Y') }}</td>
                        <td>{{ $retur->jumlahBarangRetur }}</td>
                        <td>{{ $retur->TipePengembalian }}</td>
                        <td>
                            @switch($retur->Status)
                                @case(0)
                                    <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                    @break
                                @case(1)
                                    <span class="badge bg-success">Diterima</span>
                                    @break
                                @case(2)
                                    <span class="badge bg-danger">Ditolak</span>
                                    @break
                                @case(3)
                                    <span class="badge bg-danger">Dibatalkan Pelanggan</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Status Tidak Diketahui</span>
                            @endswitch
                        </td>
                        <td>
                            <button class="btn btn-info" onclick="showDetails({{ $retur->HReturID }})">Detail</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<!-- Detail Modal -->
<div class="modal fade" id="returDetailModal" tabindex="-1" aria-labelledby="returDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returDetailModalLabel">Detail Retur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically via JavaScript -->
                <div id="returDetailsContent">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
@section('script')
<script>
 function showDetails(hreturID) {
      // Show the modal
      const modal = new bootstrap.Modal(document.getElementById('returDetailModal'));
      modal.show();

      // Display a loading message while fetching the data
      const contentDiv = document.getElementById('returDetailsContent');
      contentDiv.innerHTML = '<p>Loading...</p>';

      // Fetch the retur details from the server
      fetch(`/dashboard/retur/details/${hreturID}`)
          .then(response => response.json())
          .then(data => {
              // Populate the modal with the retur details
              let content = `
                  <h6>Nama Pemohon: ${data.user.firstName} ${data.user.lastName}</h6>
                  <p><strong>Tanggal Retur:</strong> ${data.TanggalRetur}</p>
                  <p><strong>Total Retur:</strong> Rp${data.TotalHargaRetur.toLocaleString()}</p>
                  <h6>Detail Barang Retur:</h6>
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th>Nama Barang</th>
                              <th>Jumlah</th>
                              <th>Satuan</th>
                              <th>Harga</th>
                              <th>Subtotal</th>
                              <th>Alasan</th>
                              <th>Foto Barang</th>
                          </tr>
                      </thead>
                      <tbody>
              `;

              // Loop through the Dretur items to display in the table
              data.Dretur.forEach(item => {
                content += `
                    <tr>
                        <td>${item.namaBarang}</td>
                        <td>${item.Jumlah}</td>
                        <td>${item.satuan}</td>
                        <td>Rp${item.harga.toLocaleString()}</td>
                        <td>Rp${(item.harga * item.Jumlah).toLocaleString()}</td>
                        <td>${item.alasan}</td>
                        <td>
                            <a href="${item.fotobarang}" data-lightbox="retur-image" data-title="${item.namaBarang}">
                                <img src="${item.fotobarang}" alt="Barang" class="image-zoom" width="100">
                            </a>
                        </td>
                    </tr>
                `;
            });

              content += `
                      </tbody>
                  </table>
              `;

              // Update the content in the modal
              contentDiv.innerHTML = content;
          })
          .catch(error => {
              contentDiv.innerHTML = '<p>Error loading data. Please try again later.</p>';
              console.error(error);
          });
    }
</script>
@endsection