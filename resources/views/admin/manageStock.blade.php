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
        {{-- <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#insertBarang" aria-expanded="false" aria-controls="insertBarang">
          Input barang baru
        </button> --}}
      </div>
      {{-- <div class="collapse p-2 my-2" id="insertBarang" style="background-color:rgb(233, 237, 242)">
        <form class="row g-3" action="{{url('/dashboard/barang') }}" method="POST">
          @csrf
          <div class="col-md-4">
            <label for="inputNamaBarang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control  @error('inputNamaBarang') is-invalid @enderror" id="inputNamaBarang" name="inputNamaBarang">
          </div>
          @error('inputNamaBarang')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
    
          <div class="col-md-3">
            <label for="" class="form-label">Kategori</label>
            <select class="form-select" id="inputKaryawan" name="inputKaryawan" placeholder="select Karyawan..." >
              <option value="">Select None...</option>
              @foreach ($kategori as $k)
                <option value="{{$k->id}}">{{$k->nama_category}}</option>
              @endforeach
            </select>
          </div>
          
          <div class="col-md-2">
              <label for="inputJumlah" class="form-label">jumlah</label>
              <input type="number" min="0" class="form-control  @error('inputJumlah') is-invalid @enderror" id="inputJumlah" name="inputJumlah">
          </div>
            @error('inputJumlah')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

          <div class="col-md-2">
            <label for="inputJumlah" class="form-label">Satuan</label>
            <input type="text" class="form-control  @error('inputSatuan') is-invalid @enderror" id="inputSatuan" name="inputSatuan">
          </div>
            @error('inputSatuan')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

          <div class="mb-3">
            <label for="formFile" class="form-label">Default file input example</label>
            <input class="form-control" type="file" id="formFile">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary">Insert</button>
          </div>
        </form>
      </div> --}}
    </div>
    {{-- <div class="col">
      <div class="my-3 card">
          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#insertKategori" aria-expanded="false" aria-controls="insertKategori">
            Kategori
          </button>
      </div>
      <div class="collapse p-2 my-2" id="insertKategori" style="background-color:rgb(233, 237, 242)">
        <form class="row g-3 mb-2" action="{{url('/dashboard/barang/kategori') }}" method="POST">
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
    </div> --}}
  </div>
  
</div>

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card">
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
                    <td>{{$k->namaBarang}}</td> 
                    <td>{{$k->category}}</td>
                    <td>{{$k->totalQuantity}} {{$k-> satuanTerkecil}} / {{$k->totalQuantity/$k->isiSatuanBesar}} {{$k->satuanBesar}}</td> 
                    <td> Rp.{{$k->hargaKecil}}/ Rp.{{$k->hargaBesar}}</td>
                    <td style="width: 100px;">
                      <div class="container d-flex-inline flex-row"></div>
                      <a href="#" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                      <a href="#" data-method="DELETE" data-confirm="Yakin hapus karyawan ini ?" class="btn btn-danger btn-xs pull-right delete"><i class="fa-regular fa-trash-can"></i></a>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
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
    // let table = new DataTable('#tableBarang');
</script>
@endsection