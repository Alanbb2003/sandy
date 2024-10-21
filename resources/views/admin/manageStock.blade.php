{{-- @extends('layouts.app') --}}
@extends('layouts.appAdmin')

@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <div class="my-3 card">
        <a href="{{url("/dashboard/barang/new")}}" class="btn btn-primary" type="button">
          Input barang baru
        </a>
      </div>
    </div>
  </div>
  
</div>

<div class="row justify-content-center">
    <div class="col-md-11">
      <div class="card-header"> <b>Manage Barang</b> </div>
      <div class="card-body">
          <table class="display responsive nowrap" id="tableBarang" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th data-priority="1">Nama Barang</th>
                <th>kategori</th>
                <th data-priority="3">Jumlah</th>
                <th>Harga</th>
                <th data-priority="2">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($barang as $k)
            <tr>
              <td>{{$k->id}}</td>
              <td>
                <a target="_blank" href="{{asset('images/uploads/'.$k->fotoPromosi)}}">
                  <img class="card-img-top thumbnail" src="{{asset('images/uploads/'.$k->fotoPromosi)}}" alt="Gambar Barang">
                </a>
              </td>
              <td>
                <a href="{{ url('/product/' . $k->slugBarang ) }}" class="nodecor">{{$k->namaBarang}}</a>
              </td> 
              <td>{{$k->category}}</td>
              <td>{{$k->totalQuantity}} {{$k-> satuanTerkecil}} 
                @if($k->satuanBesar)
                    / {{ round($k->totalQuantity / $k->isiSatuanBesar) }} {{$k->satuanBesar}}
                @endif
                
              </td> 
              <td> Rp.{{ number_format($k->hargaKecil, 0, ',', '.') }}
                @if($k->satuanBesar)
                  / {{ number_format($k->hargaBesar, 0, ',', '.') }}
                @endif
              </td>
              <td>
                <div class="col mb-1">
                  <!-- Button for Tambah Jumlah -->
                  <div class="row px-2 mb-2">
                    <button type="button" class="btn btn-primary w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#tambahJumlahModal" 
                            data-id="{{ $k->id }}" 
                            data-satuan-kecil="{{ $k->satuanTerkecil }}" 
                            data-satuan-besar="{{ $k->satuanBesar }}"
                            data-isibesar="{{$k->isiSatuanBesar}}">
                      Tambah Jumlah
                    </button>
                  </div>
                
                  <!-- Button for Edit -->
                  <div class="row px-2 mb-2">
                    <a href="{{ url('/dashboard/barang/edit/'.$k->id) }}" class="btn btn-primary w-100">
                      <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>
                  </div>
                
                  <!-- Button for Enable/Disable -->
                  <div class="row px-2">
                    <a href="/dashboard/barang/toggle-status/{{$k->id}}" 
                      class="btn {{ $k->Status == 1 ? 'btn-danger' : 'btn-success' }}">
                      {{ $k->Status == 1 ? 'Disable' : 'Enable' }}
                   </a>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
            </tbody>
          </table>
      </div>
    </div>

    
        <!-- Modal for Tambah Jumlah -->
        <div class="modal fade" id="tambahJumlahModal" tabindex="-1" aria-labelledby="tambahJumlahModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="tambahJumlahModalLabel">Tambah Jumlah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('admin.TambahJumlah') }}" method="POST">
                @csrf
                <div class="modal-body">
                  <input type="hidden" id="barangId" name="barangId">
                  
                  <!-- Satuan Selection -->
                  <div class="mb-3">
                    <label for="satuan" class="form-label">Pilih Satuan</label>
                    <select class="form-select" id="satuan" name="satuan">
                      <!-- Options will be populated by JavaScript -->
                    </select>
                  </div>
        
                  <!-- Amount Input -->
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
</div>
@endsection

@section('script')
<script>
  // type="text/javascript"
    $(document).ready(function(){
        $('#tableBarang').dataTable({
          responsive: true
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
      // Update the modal's hidden input field with the barangId
      var modalBarangIdInput = tambahJumlahModal.querySelector('#barangId');
      modalBarangIdInput.value = barangId;
      
      // Populate the satuan dropdown
      var satuanSelect = tambahJumlahModal.querySelector('#satuan');
      satuanSelect.innerHTML = '';  // Clear previous options
      
      // Add Satuan Kecil if available
      if (satuanKecil) {
        var optionKecil = document.createElement('option');
        optionKecil.value = 'kecil';
        optionKecil.text = satuanKecil + ' (Satuan Kecil)';
        satuanSelect.appendChild(optionKecil);
      }
      
      // Add Satuan Besar if available
      if (satuanBesar) {
        var optionBesar = document.createElement('option');
        optionBesar.value = 'besar';
        optionBesar.text = satuanBesar + ' ( isi ' + isiSatuan + ' '+satuanKecil+' )'  ;
        satuanSelect.appendChild(optionBesar);
      }
    });
    // let table = new DataTable('#tableBarang');
</script>
@endsection