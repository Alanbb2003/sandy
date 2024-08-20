{{-- @extends('layouts.app') --}}
@extends('layouts.appAdmin')
@section('content')

<!-- Button trigger modal -->
{{-- <button type="button" data-bs-toggle="modal" data-bs-target="#addKategori" class="btn btn-primary mx-5">Tambah Kategori</button>
<br>
<div class="modal fade bd-example-modal-lg" id="addKategori" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Tambah Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form class="form-inline" action="{{url('/dashboard/barang/new/kategori') }}" method="POST">
           @csrf
           <div class="row">
              <div class="col-md-5 form" style="height: 30px;">
                <input type="text" class="form-control  @error('inputkategori') is-invalid @enderror" id="inputkategori" name="inputkategori" placeholder="nama kategori">
              </div>
              @error('inputkategori')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
              <div class="col">
                <button type="submit" class="btn btn-primary">Insert</button>
              </div>
           </div>
        </form>
      </div>
      <div class="container">
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
            <td>{{$k->nama_category}}</td>
            <td style="width: 100px;">
              <div class="container d-flex-inline flex-row"></div>
              <a href="#" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
              <a href="#" data-method="DELETE" data-confirm="Yakin hapus kategori ini ?" class="btn btn-danger btn-xs pull-right delete"><i class="fa-regular fa-trash-can"></i></a>
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> --}}
{{-- <div class="container">
  <div class="col">
    <div class="my-3 card">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#insertKategori" aria-expanded="false" aria-controls="insertKategori">
          Kategori
        </button>
    </div>
    <div class="collapse p-2 my-2" id="insertKategori" style="background-color:rgb(233, 237, 242)">
      <form class="row g-3 mb-2" action="{{url('/dashboard/barang/new/kategori') }}" method="POST">
        @csrf
        <div class="col-md-5 form-floating">
          <input type="text" class="form-control  @error('inputkategori') is-invalid @enderror" id="inputkategori" name="inputkategori" placeholder="nama kategori">
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
          <td>{{$k->nama_category}}</td>
          <td style="width: 100px;">
            <div class="container d-flex-inline flex-row"></div>
            <a href="#" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
            <a href="#" data-method="DELETE" data-confirm="Yakin hapus kategori ini ?" class="btn btn-danger btn-xs pull-right delete"><i class="fa-regular fa-trash-can"></i></a>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div> --}}

<!-- Button trigger kategori -->
<div class="mx-5 my-3 card">
  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" href="#insertKategori" data-bs-target="#insertKategori" aria-expanded="false" aria-controls="insertKategori">
    Tambah Kategori
  </button>
</div>
<br>
<!-- Button trigger kategori -->
<!-- ISI PAGE -->
<div class="container d-flex" id="insertBarang" style="background-color:rgb(233, 237, 242)">

<div class="collapse border border-primary p-2 mx-3 my-2" id="insertKategori" style="background-color:rgb(233, 237, 242)">

  <form class="row g-3 mb-2" action="{{url('/dashboard/barang/new/kategori') }}" method="POST">
    @csrf
    <div class="col-md-5 form-floating">
      <input type="text" class="form-control  @error('inputkategori') is-invalid @enderror" id="inputkategori" name="inputkategori" placeholder="nama kategori">
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
        {{-- <a href="#" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a> --}}
        {{-- <a href="#" data-method="DELETE" data-confirm="Yakin hapus kategori ini ?" class="btn btn-danger btn-xs pull-right delete"><i class="fa-regular fa-trash-can"></i></a> --}}
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>

<!-- ISI Data barang -->
    <form class="row g-3" action="{{url('/dashboard/barang/new') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3 d-flex">
        <img id="frame" src="" width="100px" height="100px" class="img-thumbnail mt-1"/>
        <div class="container-fluid">
          <label for="thumbnail" class="form-label">Foto produk promosi</label>
          <input class="form-control" type="file" id="thumbnail" name="thumbnail">
          @error('thumbnail')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <label for="formFile" class="form-label">Gambar</label>
        <input class="form-control" type="file" id="images" name="images[]" multiple>
        <div id="frames"></div>
      </div>

      <div class="col-md-4">
        <label for="inputNamaBarang" class="form-label">Nama Barang</label>
        <input type="text" class="form-control  @error('inputNamaBarang') is-invalid @enderror" id="inputNamaBarang" name="inputNamaBarang">
        @error('inputNamaBarang')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
    
      <div class="col-md-3">
        <label for="" class="form-label">Kategori</label>
        <select class="form-select" id="inputKategori" name="inputKategori" placeholder="select Kategori..." >
          {{-- <option value="">Select None...</option> --}}
          @foreach ($kategori as $k)
            <option value="{{$k->id}}">{{$k->nama_category}}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label for="inputDeskripsi" class="form-label">Deskripsi</label>
        <input type="text" class="form-control  @error('inputDeskripsi') is-invalid @enderror" id="inputDeskripsi" name="inputDeskripsi">
      </div>
      @error('inputDeskripsi')
      <div class="alert alert-danger">{{ $message }}</div>
      @enderror
      
      <div class="col-md-2">
          <label for="inputJumlah" class="form-label">jumlah terkecil</label>
          <input type="number" min="0" class="form-control  @error('inputJumlahKecil') is-invalid @enderror" id="inputJumlahKecil" name="inputJumlahKecil">
          @error('inputJumlahKecil')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      
      <div class="col-md-2">
        <label for="inputJumlah" class="form-label">Satuan terkecil</label>
        <input type="text" class="form-control  @error('inputSatuanKecil') is-invalid @enderror" id="inputSatuanKecil" name="inputSatuanKecil">
        @error('inputSatuanKecil')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-2">
        <label for="inputJumlah" class="form-label">jumlah terkecil dalam -></label>
        <input type="number" min="0" class="form-control  @error('inputJumlahBesar') is-invalid @enderror" id="inputJumlahBesar" name="inputJumlahBesar">
        @error('inputJumlahBesar')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
    
      <div class="col-md-2">
        <label for="inputJumlah" class="form-label">Satuan terbesar</label>
        <input type="text" class="form-control  @error('inputSatuanBesar') is-invalid @enderror" id="inputSatuanBesar" name="inputSatuanBesar">
        @error('inputSatuanBesar')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="d-flex flex-row">
        <div class="col-md-2 me-3">
          <label for="inputJumlah" class="form-label"> Harga jumlah terkecil</label>
          Rp.<input type="number" min="0" class="form-control  @error('inputHargaKecil') is-invalid @enderror" id="inputHargaKecil" name="inputHargaKecil">
          @error('inputHargaKecil')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
        </div>
  
        <div class="col-md-2">
            <label for="inputJumlah" class="form-label">Harga jumlah terbesar</label>
            Rp.<input type="number" min="0" class="form-control  @error('inputHargaBesar') is-invalid @enderror" id="inputHargaBesar" name="inputHargaBesar">
            @error('inputHargaBesar')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
      </div>
     
      <div class="col-12">
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
        <form id="editCategoryForm">
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
    $('#thumbnail').change(function preview() {
    frame.src=URL.createObjectURL(event.target.files[0]);
    });
    
    $('#tableKategori').dataTable(

    );

    $('#exampleModalCenter').on('shown.bs.modal', function () {
      $('#myInput').trigger('focus')
    })

    $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // When edit button is clicked
  $('.edit-item').on('click', function(){
    var categoryId = $(this).data('item-id');
    var categoryName = $(this).closest('tr').find('.namaKategori').text();

    // Fill the modal with the data
    $('#categoryId').val(categoryId);
    $('#categoryName').val(categoryName);

    // Clear previous error messages
    $('#editCategoryForm .error-message').remove();

    // Open the modal
    $('#editCategoryModal').modal('show');
    // var myModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    // myModal.show();
  });

  // Handle form submission
  $('#editCategoryForm').on('submit', function(e){
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      url: '/dashboard/barang/new/kategori/update-category', // Change this to your update URL
      type: 'POST',
      data: formData,
      success: function(response) {
        // Close the modal
        var myModalEl = document.getElementById('editCategoryModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();

        // Optionally, update the category name in the table
        $('button[data-item-id="' + response.id + '"]').closest('tr').find('.namaKategori').text(response.nama_category);

        // Optionally, show a success message
        alert('Kategori berhasil diperbarui!');

          // Refresh the category select dropdown
          refreshCategoryDropdown();
      },
      error: function(response) {
        // Display error message
        var errorMessage = response.responseJSON ? response.responseJSON.error : 'An unexpected error occurred.';
        var errorElement = $('<div class="error-message alert alert-danger mt-2"></div>').text(errorMessage);
        $('#editCategoryForm').prepend(errorElement);
      }
    });
  });
  function refreshCategoryDropdown() {
    $.ajax({
      url: '/dashboard/barang/new/get-categories', // Change this to your URL to fetch categories
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
});
</script>
@endsection