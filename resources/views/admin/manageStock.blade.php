@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <div class="my-3 card">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#insertBarang" aria-expanded="false" aria-controls="collapseExample">
          Input barang baru
        </button>
      </div>
      <div class="collapse card p-2 my-2" id="insertBarang" style="background-color:rgb(220, 223, 227)">
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

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Insert</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col">
      <div class="my-3 card">
          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#insertKategori" aria-expanded="false" aria-controls="collapseExample">
            Kategori
          </button>
      </div>
      <div class="collapse card p-2 my-2" id="insertKategori" style="background-color:rgb(220, 223, 227)">
        <form class="row g-3 mb-2" action="{{url('/dashboard/barang') }}" method="POST">
          @csrf
          <div class="col-md-5 form-floating">
            <input type="text" class="form-control  @error('inputkategori') is-invalid @enderror" id="inputkategori" name="inputkategori" placeholder="nama kategori">
            <label for="inputNamaBarang">Nama kategori</label>
          </div>
          @error('inputkategori')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
          <div class="col-md-3 mt-4">
            <button type="submit" class="btn btn-primary">Insert</button>
          </div>
        </form>

        <table class="table border" id="tableKategori">
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
            {{-- <td>{{date("d/M/Y",strtotime($k->crea))}}</td>
            <td>{{date("d/m/Y",strtotime($k->Tgl_Lahir))}}</td> --}}
            {{-- <td style="width: 100px;">
              <div class="container d-flex-inline flex-row"></div>
              <a href="{{ url("admin/employee/".$k->KaryawanID) }}" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
              <a href="{{url("/admin/employee/".$k->KaryawanID."/destroy")}}" data-method="DELETE" data-confirm="Yakin hapus karyawan ini ?" class="btn btn-danger btn-xs pull-right delete"><i class="fa-regular fa-trash-can"></i></a>
            </td> --}}
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
</div>

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card">
            <div class="card-header"> <b>Manage Barang</b> </div>
            <div class="card-body">
                <table class="table border" id="tableBarang" name=>
                  <thead>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                  @foreach ($barang as $k)
                  <tr>
                    <td>{{$k->id}}</td>
                    <td>{{$k->nama_barang}}</td> 
                    <td>{{$k->category}}</td>
                    <td>{{$k->deskripsi}}</td>
                    <td>{{$k->jumlah_barang}}</td>
                    <td>{{$k->satuan_jumlah}}</td>
                    {{-- <td>{{date("d/M/Y",strtotime($k->crea))}}</td>
                    <td>{{date("d/m/Y",strtotime($k->Tgl_Lahir))}}</td> --}}
                    {{-- <td style="width: 100px;">
                      <div class="container d-flex-inline flex-row"></div>
                      <a href="{{ url("admin/employee/".$k->KaryawanID) }}" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                      <a href="{{url("/admin/employee/".$k->KaryawanID."/destroy")}}" data-method="DELETE" data-confirm="Yakin hapus karyawan ini ?" class="btn btn-danger btn-xs pull-right delete"><i class="fa-regular fa-trash-can"></i></a>
                    </td> --}}
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
    let table = new DataTable('#tableBarang');
</script>
@endsection