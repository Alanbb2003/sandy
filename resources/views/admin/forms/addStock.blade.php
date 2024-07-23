@extends('layouts.app')
@section('content')
<!-- Button trigger modal -->
<!-- Button trigger modal -->
<button type="button" data-bs-toggle="modal" data-bs-target="#addKategori" class="btn btn-primary mx-5">Tambah Kategori</button>
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
</div>
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
<div class="modal fade" id="editModal-" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form class="" action="{{url('/dashboard/barang/new/kategori/'.$id.'/update') }}" method="POST">
           @csrf
            <div class="col-md-6">
              <label for="EditNamaStatus" class="form-label">Nama Status</label>
              <input type="text" class="form-control  @error('EditNamaStatus') is-invalid @enderror" id="EditNamaStatus" name="EditNamaStatus" value="{{$u->Nama_Status}}">
            </div>
            @error('inputNamaStatus')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
              <label for="EditDeskripsi" class="form-label">Deskripsi</label>
              <input type="text" class="form-control  @error('EditDeskripsi') is-invalid @enderror" id="EditDeskripsi" name="EditDeskripsi" value="{{$u->Deskripsi}}">
            </div>
            @error('inputDeskripsi')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="col-12 mt-2">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>

<br>
<div class="container" id="insertBarang" style="background-color:rgb(233, 237, 242)">
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

      <div class="col-md-2">
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
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
    </form>
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
});
</script>
@endsection