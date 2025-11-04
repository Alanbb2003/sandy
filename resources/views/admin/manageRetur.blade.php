@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <h4>Permohonan Retur</h4>
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
            <td>{{ $retur->TanggalRetur }}</td>
            <td>{{ $retur->jumlahBarangRetur }}</td>
            <td>
                {{$retur->TipePengembalian}}
                @if (!is_null($retur->statusPerubahan))
                    <br>
                    <span class="text-muted small">
                        <strong>Perubahan:</strong>
                        <span data-bs-toggle="tooltip" title="
                            @if ($retur->statusPerubahan == 1)
                                Penukaran Barang → Pengembalian Dana
                            @elseif ($retur->statusPerubahan == 2)
                                Pengembalian Dana → Penukaran Barang
                            @endif
                        ">
                            @if ($retur->statusPerubahan == 1)
                                Penukaran → Pengembalian
                            @elseif ($retur->statusPerubahan == 2)
                                Pengembalian → Penukaran
                            @endif
                        </span>
                    </span>
                    <br>
                    <span class="text-muted small">
                        <strong>Alasan:</strong>
                        <span data-bs-toggle="tooltip" title="{{ $retur->AlasanPerubahan }}">
                            {{ Str::limit($retur->AlasanPerubahan, 15) }}
                        </span>
                    </span>
                @endif
            </td>
            <td> 
               @switch($retur->Status)
                    @case(0)
                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                        @break
                    @case(1)
                        <span class="badge bg-success">Diterima</span>
                        @foreach ($admins as $admin)
                            @if ($retur->FkPenerima == $admin->id)
                                <br><b>Penanggung Jawab: </b><p>{{$admin->name}}</p>
                            @endif
                        @endforeach
                        @break
                    @case(2)
                        <span class="badge bg-danger">Ditolak</span>
                        @foreach ($admins as $admin)
                            @if ($retur->FkPenerima == $admin->id)
                                <br><b>Penanggung Jawab: </b><p>{{$admin->name}}</p>
                            @endif
                        @endforeach
                        @break
                    @case(3)
                        <span class="badge bg-danger">Dibatalkan Pelanggan</span>
                    @break
                    @default
                        <span class="badge bg-secondary">Status Tidak Diketahui</span>
                @endswitch
            </td>
            <td>
                 <!-- Detail Button -->
                <button class="btn btn-info w-100 btn-sm mb-1" onclick="showDetails({{ $retur->HReturID }})">
                    <i class="fa-solid fa-info-circle"></i> Detail
                </button>

                <!-- Conditional Buttons for Pending Status -->
                @if ($retur->Status == 0)
                    <div class="d-grid gap-2">
                        <!-- Change Return Type Button -->
                        <button class="btn btn-warning w-100 btn-sm" onclick="changeReturnType({{ $retur->HReturID }})">
                            <i class="fa-solid fa-edit"></i>Ubah Tipe
                        </button>

                        <!-- Confirm Button -->
                        <button class="btn btn-success w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="{{ $retur->HReturID }}">
                            <i class="fa-solid fa-check"></i> Confirm
                        </button>

                        <!-- Reject Button -->
                        <button class="btn btn-danger w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $retur->HReturID }}">
                            <i class="fa-solid fa-times"></i> Reject
                        </button>
                    </div>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>
<!-- Modal untuk mengubah tipe pengembalian -->
<div id="changeReturnTypeModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ubah Tipe Pengembalian</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="changeReturnTypeForm">
                  <div class="form-group mb-3">
                      <label for="tipePengembalian">Pilih Tipe Pengembalian:</label>
                      <select class="form-select" id="tipePengembalian" name="tipePengembalian" required>
                          <option value="Pengembalian Dana">Pengembalian Dana</option>
                          <option value="Penukaran Barang">Penukaran Barang</option>
                      </select>
                  </div>
                  <div class="form-group mb-3">
                      <label for="reasonChange">Alasan Perubahan:</label>
                      <textarea class="form-control" id="reasonChange" name="reasonChange" rows="3" placeholder="Masukkan alasan perubahan..." required></textarea>
                  </div>
                  <input type="hidden" id="returID" name="returID">
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="button" class="btn btn-primary" onclick="submitReturnTypeChange()">Simpan Perubahan</button>
          </div>
      </div>
  </div>
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

<!-- Modal to confirm return -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <form method="POST" action="{{ route('admin.confirmRetur') }}">
          @csrf
          <input type="hidden" name="returID" id="confirmReturID">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="confirmModalLabel">Confirm Return</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  Are you sure you want to confirm this return request?
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success">Confirm</button>
              </div>
          </div>
      </form>
  </div>
</div>

<!-- Modal to reject return -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <form method="POST" action="{{ route('admin.rejectRetur') }}">
          @csrf
          <input type="hidden" name="returID" id="rejectReturID">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="rejectModalLabel">Reject Return</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  Are you sure you want to reject this return request?
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Reject</button>
              </div>
          </div>
      </form>
  </div>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function(){
        $('#tableRetur').dataTable({
          responsive: true,
          pageLength:10
        } );
    });
    lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true
    })
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
  function changeReturnType(returID) {
      // Mengisi hidden input dengan HReturID untuk dikirim
      document.getElementById('returID').value = returID;
      
      // Menampilkan modal
      var modal = new bootstrap.Modal(document.getElementById('changeReturnTypeModal'));
      modal.show();
  }

  function submitReturnTypeChange() {
        const returID = document.getElementById('returID').value;
        const tipePengembalian = document.getElementById('tipePengembalian').value;
        const reasonChange = document.getElementById('reasonChange').value;

        // Determine statusPerubahan value
        const statusPerubahan = tipePengembalian === "Pengembalian Dana" ? 1 : 2;

        // Kirim data ke server untuk mengupdate tipe pengembalian
        fetch(`/dashboard/update-return-type/${returID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                tipePengembalian, 
                reasonChange, 
                statusPerubahan 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Tipe pengembalian berhasil diubah.");
                location.reload(); // Reload halaman untuk melihat perubahan
            } else {
                alert("Terjadi kesalahan saat mengubah tipe pengembalian.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan saat mengubah tipe pengembalian.");
        });
    }

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

    document.getElementById('confirmModal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var returID = button.getAttribute('data-id');
        var modal = this;
        modal.querySelector('#confirmReturID').value = returID;
    });

    document.getElementById('rejectModal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var returID = button.getAttribute('data-id');
        var modal = this;
        modal.querySelector('#rejectReturID').value = returID;
    });
</script>
@endsection