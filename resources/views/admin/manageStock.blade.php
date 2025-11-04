@extends('layouts.appAdmin')

@section('content')
   <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="row g-4">
                            <div class="col-md-6">
                                {{-- <h5>General Filters</h5> --}}
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label"><strong>Nama Barang</strong></label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama Barang" value="{{ request('name') }}">
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="category" class="form-label"><strong>Kategori</strong></label>
                                        <select name="category" id="category" class="form-select">
                                            <option value="">Semua Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nama_category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-md-6">
                                {{-- <h5>Range Filters</h5> --}}
                                <div class="row g-3">

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Harga Satuan Kecil</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="price_min_small" class="form-control" placeholder="Min Price" value="{{ request('price_min_small') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="price_max_small" class="form-control" placeholder="Max Price" value="{{ request('price_max_small') }}">
                                        </div>
                                    </div>

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Harga Satuan Besar</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="price_min_big" class="form-control" placeholder="Min Price" value="{{ request('price_min_big') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="price_max_big" class="form-control" placeholder="Max Price" value="{{ request('price_max_big') }}">
                                        </div>
                                    </div>

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Stock Satuan Kecil</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="stok_min" class="form-control" placeholder="Min Stock" value="{{ request('stok_min') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="stok_max" class="form-control" placeholder="Max Stock" value="{{ request('stok_max') }}">
                                        </div>
                                    </div>

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Stock Satuan Besar</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="stok_min_big" class="form-control" placeholder="Min Stock" value="{{ request('stok_min_big') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="stok_max_big" class="form-control" placeholder="Max Stock" value="{{ request('stok_max_big') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fa-solid fa-filter"></i>    
    </button>
    
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="my-3 card">
        <a href="{{ url('/dashboard/barang/new') }}" class="btn btn-primary" type="button">
          Input Barang Baru
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-11">
    <div class="card">
      <div class="card-header">
        <h4 class="text-center">Manage Barang</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered display nowrap" id="tableBarang" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th data-priority="1">Nama Barang</th>
                <th>Kategori</th>
                <th data-priority="3">Jumlah</th>
                <th>Harga Kecil / Harga Besar</th>
                <th data-priority="2">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $k)
              <tr>
                <td>{{ $k->id }}</td>
                <td>
                  @if($k->fotoPromosi)
                  <a href="#" class="openImageModal" data-bs-toggle="modal" data-bs-target="#imageModal" 
                     data-image="{{ asset('images/uploads/'.$k->fotoPromosi) }}" 
                     data-title="{{ $k->namaBarang }}">
                    <img src="{{ asset('images/uploads/'.$k->fotoPromosi) }}" alt="Product Image" style="width: 100px; height: auto;">
                  </a>
                  @else
                  No image available
                  @endif
                </td>
                 <td style="max-width: 200px; word-wrap: break-word; white-space: normal;">
                  <a href="{{ url('/product/' . $k->slugBarang) }}" class="nodecor">{{ $k->namaBarang }}</a>
                </td>
                <td>{{ $k->category->nama_category ?? 'N/A' }}</td>
                <td>
                  {{ $k->totalQuantity }} {{ $k->satuanTerkecil }}
                  @if($k->satuanBesar)
                  / {{ round($k->totalQuantity / $k->isiSatuanBesar) }} {{ $k->satuanBesar }} <p>(isi {{$k->isiSatuanBesar}} {{$k->satuanTerkecil}})</p>
                  @endif
                </td>
                <td>
                  Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}
                  @if($k->satuanBesar)
                  / Rp. {{ number_format($k->hargaBesar, 0, ',', '.') }}
                  @endif
                </td>
                <td>
                  <div class="d-flex flex-column">
                    <button type="button" class="btn btn-primary mb-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#tambahJumlahModal" 
                            data-id="{{ $k->id }}" 
                            data-satuan-kecil="{{ $k->satuanTerkecil }}" 
                            data-satuan-besar="{{ $k->satuanBesar }}"
                            data-isibesar="{{ $k->isiSatuanBesar }}">
                      Tambah Jumlah
                    </button>
                    <a href="{{ url('/dashboard/barang/edit/'.$k->id) }}" class="btn btn-secondary mb-2">
                      <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>
                    <a href="/dashboard/barang/toggle-status/{{ $k->id }}" 
                      class="btn {{ $k->Status == 1 ? 'btn-danger' : 'btn-success' }}">
                      {{ $k->Status == 1 ? 'Disable' : 'Enable' }}
                    </a>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
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

<!-- Modal Tambah Jumlah -->
<div class="modal fade" id="tambahJumlahModal" tabindex="-1" aria-labelledby="tambahJumlahModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahJumlahModalLabel">Tambah Jumlah Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.TambahJumlah') }}" method="POST">
        @csrf
        <div class="modal-body">
          <input type="hidden" id="barangId" name="barangId">
          <div class="mb-3">
            <label for="satuan" class="form-label">Pilih Satuan</label>
            <select class="form-select" id="satuan" name="satuan"></select>
          </div>
          <div class="mb-3">
            <label for="amount" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="amount" name="amount" placeholder="Masukkan jumlah" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#tableBarang').dataTable({
          responsive: true,
          order: [[0, 'desc']]
        } );
        $('#tableKategori').dataTable(
          
        );
    });

    var tambahJumlahModal = document.getElementById('tambahJumlahModal');
  
    tambahJumlahModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;

      var barangId = button.getAttribute('data-id');
      var satuanKecil = button.getAttribute('data-satuan-kecil');
      var satuanBesar = button.getAttribute('data-satuan-besar');
      var isiSatuan = button.getAttribute('data-isiBesar')

      var modalBarangIdInput = tambahJumlahModal.querySelector('#barangId');
      modalBarangIdInput.value = barangId;
      
      var satuanSelect = tambahJumlahModal.querySelector('#satuan');
      satuanSelect.innerHTML = '';  
      
      if (satuanKecil) {
        var optionKecil = document.createElement('option');
        optionKecil.value = 'kecil';
        optionKecil.text = satuanKecil + ' (Satuan Kecil)';
        satuanSelect.appendChild(optionKecil);
      }
      
      if (satuanBesar) {
        var optionBesar = document.createElement('option');
        optionBesar.value = 'besar';
        optionBesar.text = satuanBesar + ' ( isi ' + isiSatuan + ' '+satuanKecil+' )'  ;
        satuanSelect.appendChild(optionBesar);
      }
    });

    // untuk buka modal gambar
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
</script>
@endsection