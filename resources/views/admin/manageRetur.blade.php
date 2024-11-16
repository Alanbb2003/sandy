@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <h4>Permohonan Retur</h4>

    <table class="table table-bordered table-sm compact-table" id="tableRetur">
        <thead>
            <tr>
                <th>ID</th>
                <th>Barang</th>
                <th>Image</th> 
                <th>Pembeli</th>
                <th>Jumlah</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Tanggal request</th>
                <th>Tipe Pengembalian</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          @foreach($returRequests as $retur)
          <tr>
              <td>{{ $retur->id }}</td>
              <td>{{ $retur->dtrans->product->namaBarang }}</td>
              <td>
                  @if($retur->fotoBarang)
                      <a href="#" class="openImageModal" data-bs-toggle="modal" data-bs-target="#imageModal" 
                          data-image="{{ asset('images/userUpload/' . $retur->fotoBarang) }}" 
                          data-title="{{ $retur->dtrans->product->namaBarang }}">
                          <img src="{{ asset('images/userUpload/' . $retur->fotoBarang) }}" alt="Product Image" style="width: 100px; height: auto;">
                      </a>
                  @else
                      No image available
                  @endif
              </td>
              <td>{{ $retur->user->firstName }} {{ $retur->user->lastName }} ({{ $retur->user->email }}) (<strong>{{ $retur->bankName }} {{ $retur->accountNumber }}</strong>)</td>
              <td>{{ $retur->jumlahBarangRetur }} {{ $retur->satuanBarangRetur }}</td>
              <td>Rp.{{ number_format($retur->hargaPerBarang, 2) }}</td>
              <td>Rp.{{ number_format($retur->subTotal, 2) }}</td>
              <td>{{ $retur->tanggalRetur }}</td>
              <td>{{ $retur->TipePengembalian}}</td>
              <td>{{ $retur->alasanRetur }}</td>
              <td>
                  @switch($retur->status)
                      @case(0)
                          <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                          @break
                      @case(1)
                          <span class="badge bg-success">Diterima</span>
                          @break
                      @case(2)
                          <span class="badge bg-danger">Ditolak</span>
                          @break
                      @default
                          <span class="badge bg-secondary">Unknown</span>
                  @endswitch
              </td>
              <td>
                  @if($retur->status == 0)
                      <div class="d-grid gap-2">
                          <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="{{ $retur->id }}">Terima</button>
                          <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $retur->id }}">Tolak</button>
                      </div>
                  @else
                      <span class="text-muted">No actions available</span>
                  @endif
              </td>
          </tr>
          @endforeach
        </tbody>
    </table>
</div>

<!-- Modal gambar-->
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

<!-- Modal konfirmasi -->
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

<!-- modal tolak -->
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

    document.addEventListener('DOMContentLoaded', function() {
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('imageModalLabel');

        document.querySelectorAll('.openImageModal').forEach(item => {
            item.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-image');
                const imageTitle = this.getAttribute('data-title');
                modalImage.src = imageSrc;
                modalTitle.textContent = imageTitle;
            });
        });
    });

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