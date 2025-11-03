@extends('layouts.appAdmin')
@section('content')
<div class="mx-5 my-3 card">
  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" href="#insertKategori" data-bs-target="#insertKategori" aria-expanded="false" aria-controls="insertKategori">
    Tambah Dan Ubah Kategori
  </button>
</div>
<br>

<div class="container d-flex" id="insertBarang" style="background-color:rgb(233, 237, 242)">

<div class="collapse border border-primary p-2 mx-3 my-2" id="insertKategori" style="background-color:rgb(233, 237, 242)">

  <form class="row g-3 mb-2" action="{{url('/dashboard/barang/new/kategori') }}" method="POST">
    @csrf
    <div class="col-md-5 form-floating">
      <input type="text" class="form-control  @error('inputkategori') is-invalid @enderror" id="inputkategori" name="inputkategori" placeholder="nama kategori" required>
      <label for="inputNamaKategori">Nama kategori</label>
    </div>
    @error('inputkategori')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="col-md-3 mt-4">
      <button type="submit" class="btn btn-primary">Insert</button>
    </div>
  </form>

  <table class="display responsive nowrap" id="tableKategori" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>kategori</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($kategori as $k)
    <tr>
      <td>{{$k->id}}</td>
      <td class="namaKategori">{{$k->nama_category}}</td>
      <td style="width: 100px;">
        <div class="container d-flex-inline flex-row"></div>
        <button type="button" class="btn btn-success edit-item" id="edit-item" data-item-id="{{$k->id}}"><i class="fa-regular fa-pen-to-square"></i></button>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>

<!-- ISI Data barang -->
    <form class="container py-3" action="{{url('/dashboard/barang/new') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row mb-3 align-items-center">
          <div class="col-auto">
              <img id="frame" src="" width="100" height="100" class="img-thumbnail mt-1" alt="Product Preview"/>
          </div>
          <div class="col">
              <label for="thumbnail" class="form-label">Foto produk promosi</label>
              <input class="form-control" type="file" id="thumbnail" name="thumbnail" required>
              @error('thumbnail')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
      </div>
  
      <div class="mb-3">
          <label for="images" class="form-label">Gambar Tambahan</label>
          <input class="form-control" type="file" id="images" name="images[]" multiple>
          <div id="frames"></div>
      </div>
  
      <div class="row g-3">
          <div class="col-md-6">
              <label for="inputNamaBarang" class="form-label">Nama Barang</label>
              <input type="text" class="form-control @error('inputNamaBarang') is-invalid @enderror" id="inputNamaBarang" name="inputNamaBarang" required>
              @error('inputNamaBarang')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
  
          <div class="col-md-6">
              <label for="inputKategori" class="form-label">Kategori</label>
              <select class="form-select" id="inputKategori" name="inputKategori" required>
                  <option selected disabled value="">Pilih Kategori...</option>
                  @foreach ($kategori as $k)
                      <option value="{{$k->id}}">{{$k->nama_category}}</option>
                  @endforeach
              </select>
          </div>
      </div>
  
      <div class="mb-3 mt-3">
          <label for="inputDeskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control @error('inputDeskripsi') is-invalid @enderror" id="inputDeskripsi" name="inputDeskripsi" rows="3" placeholder="Enter your text here..." required></textarea>
          @error('inputDeskripsi')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
  
      <div class="row g-3">
          <div class="col-md-3">
              <label for="inputJumlahKecil" class="form-label">Jumlah Terkecil</label>
              <input type="number" min="0" class="form-control @error('inputJumlahKecil') is-invalid @enderror" id="inputJumlahKecil" name="inputJumlahKecil" required>
              @error('inputJumlahKecil')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
  
          <div class="col-md-3">
              <label for="inputSatuanKecil" class="form-label">Satuan Terkecil</label>
              <input type="text" class="form-control @error('inputSatuanKecil') is-invalid @enderror" id="inputSatuanKecil" name="inputSatuanKecil" required>
              @error('inputSatuanKecil')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
  
          <div class="col-md-3">
              <label for="inputJumlahBesar" class="form-label">Jumlah Terbesar Dalam</label>
              <input type="number" min="0" class="form-control @error('inputJumlahBesar') is-invalid @enderror" id="inputJumlahBesar" name="inputJumlahBesar">
              @error('inputJumlahBesar')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
  
          <div class="col-md-3">
              <label for="inputSatuanBesar" class="form-label">Satuan Terbesar</label>
              <input type="text" class="form-control @error('inputSatuanBesar') is-invalid @enderror" id="inputSatuanBesar" name="inputSatuanBesar">
              @error('inputSatuanBesar')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
      </div>
  
      <div class="row g-3 mt-3">
          <div class="col-md-3">
              <label for="inputHargaKecil" class="form-label">Harga Jumlah Terkecil</label>
              <div class="input-group">
                  <span class="input-group-text">Rp.</span>
                  <input type="number" min="0" class="form-control @error('inputHargaKecil') is-invalid @enderror" id="inputHargaKecil" name="inputHargaKecil">
              </div>
              @error('inputHargaKecil')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
  
          <div class="col-md-3">
              <label for="inputHargaBesar" class="form-label">Harga Jumlah Terbesar</label>
              <div class="input-group">
                  <span class="input-group-text">Rp.</span>
                  <input type="number" min="0" class="form-control @error('inputHargaBesar') is-invalid @enderror" id="inputHargaBesar" name="inputHargaBesar">
              </div>
              @error('inputHargaBesar')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
      </div>
  
      <div class="d-flex justify-content-end mt-4">
          <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
    </form>
  </div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCategoryForm" action="{{route('category.update') }}">
          @csrf
          <div class="form-group">
            <label for="categoryName">Nama Kategori</label>
            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
          </div>
          <input type="hidden" id="categoryId" name="categoryId">
          <button type="submit" class="btn btn-primary my-1">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){
  
    $('#images').change(function(){
        $("#frames").html('');
        for (var i = 0; i < $(this)[0].files.length; i++) {
            $("#frames").append('<img src="'+window.URL.createObjectURL(this.files[i])+'" width="120px" height="120px" class="img-thumbnail mt-1"/>');
        }
    });
    $('#thumbnail').change(function preview(event) {
      const frame = document.getElementById('frame');
      frame.src = URL.createObjectURL(event.target.files[0]);
   });
    
    $('#tableKategori').dataTable(

    );

    $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(document).on('click', '.edit-item', function () {
        var categoryId = $(this).data('item-id');
        var categoryName = $(this).closest('tr').find('.namaKategori').text();

        $('#categoryId').val(categoryId);
        $('#categoryName').val(categoryName);

        $('#editCategoryForm .error-message').remove();
        $('#editCategoryModal').modal('show');
    });


  $('#editCategoryForm').on('submit', function(e){
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      url: '/dashboard/barang/new/kategori/update-category',
      type: 'POST',
      data: formData,
      success: function(response) {
        var myModalEl = document.getElementById('editCategoryModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();

        $('button[data-item-id="' + response.id + '"]').closest('tr').find('.namaKategori').text(response.nama_category);

        refreshCategoryDropdown();
      },
      error: function(response) {
        var errorMessage = response.responseJSON ? response.responseJSON.error : 'An unexpected error occurred.';
        var errorElement = $('<div class="error-message alert alert-danger mt-2"></div>').text(errorMessage);
        $('#editCategoryForm').prepend(errorElement);
      }
    });
  });
  
});
function refreshCategoryDropdown() {
    $.ajax({
      url: '/dashboard/barang/new/get-categories',
      type: 'GET',
      success: function(response) {
        var select = $('#inputKategori');
        select.empty();
        response.forEach(function(category) {
          select.append(new Option(category.nama_category, category.id));
        });
      },
      error: function(response) {
        alert('Failed to refresh categories.');
      }
    });
  }
</script>
@endsection