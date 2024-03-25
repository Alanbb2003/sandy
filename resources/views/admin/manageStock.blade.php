@extends('layouts.app')

@section('content')
<div class="container">
    <div class="my-3 card">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#insertForm" aria-expanded="false" aria-controls="collapseExample">
         Input barang baru
        </button>
    </div>
        <div class="collapse card p-2 my-2" id="insertForm">
          <form class="row g-3" action="{{url('/dashboard/barang') }}" method="POST">
            @csrf
            <div class="col-md-4">
              <label for="inputNamaBarang" class="form-label">Nama Barang</label>
              <input type="text" class="form-control  @error('inputNamaBarang') is-invalid @enderror" id="inputNamaBarang" name="inputNamaBarang">
            </div>
            @error('inputNamaBarang')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            {{-- <div class="col-md-6">
              <label for="" class="form-label">Akun Milik</label>
              <select class="form-select" id="inputKaryawan" name="inputKaryawan" placeholder="select Karyawan..." style="width: 50%">
                <option value="">Select None...</option>
                @foreach ($karyawan as $k)
                  <option value="{{$k->KaryawanID}}">{{$k->Nama_karyawan}}</option>
                @endforeach
              </select>
            </div> --}}
            <div class="col-md-2">
                <label for="inputJumlah" class="form-label">jumlah</label>
                <input type="number" min="0" class="form-control  @error('inputJumlah') is-invalid @enderror" id="inputJumlah" name="inputJumlah">
            </div>
              @error('inputJumlah')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror

            <div class="form-group">
              <label for="inputLastName" class="form-label">Role</label>
              <select class="form-select" name="inputRole" id="inputRole">
                  <option value="Admin">Admin</option>
                  <option value="Karyawan">Karyawan</option>
              </select>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Insert</button>
            </div>
          </form>
        </div>
      </div>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header"> <b>Manage Barang</b> </div>
                <div class="card-body">
                    <table class="table border" id="tableBarang">
                      <thead>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Action</th>
                      </thead>
                      <tbody>
                          <tr>
                            <td></td>
                            <td></td> 
                            <td></td>
                            <td></td>
                            {{-- <td>{{$k->No_KTP}}</td> --}}
                          </tr>
                      </tbody>
                    </table>
                </div>
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