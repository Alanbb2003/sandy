@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card col mx-3">
            <form action="{{ url('/address/add') }}" method="POST">
                @csrf
                <div class="mb-1">
                    <label for="InputnamaDepan" class="form-label">Nama Depan</label>
                    <input id="InputnamaDepan" type="text" class="form-control @error('InputnamaDepan') is-invalid @enderror" name="InputnamaDepan" value="{{ old('InputnamaDepan') }}" required autocomplete="InputnamaDepan" autofocus placeholder="Nama Depan">
                    @error('InputnamaDepan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="mb-1">
                    <label for="InputnamaBelakang" class="form-label">Nama Belakang</label>
                    <input id="InputnamaBelakang" type="text" class="form-control @error('InputnamaBelakang') is-invalid @enderror" name="InputnamaBelakang" value="{{ old('InputnamaBelakang') }}" required autocomplete="InputnamaBelakang" autofocus placeholder="Nama Belakang">
                    @error('InputnamaBelakang')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <div class="mb-1">
                    <label for="InputDetail" class="form-label">Detail Alamat</label>
                    <input id="InputDetail" type="text" class="form-control @error('InputDetail') is-invalid @enderror" name="InputDetail" value="{{ old('InputDetail') }}" required autocomplete="InputDetail" autofocus placeholder="Detail Alamat">
                    @error('InputDetail')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <div class="mb-1">
                    <label for="InputnoHp" class="form-label">Nomor telefon</label>
                    <input type="text" class="form-control" id="InputnoHp" name="InputnoHp" value="{{ old('InputnoHp') }}" placeholder="Nomor telefon">
                </div>
            
                <div class="mb-1">
                    <label for="InputKodePos" class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" id="InputKodePos" name="InputKodePos" value="{{ old('InputKodePos') }}" placeholder="Kode Pos">
                </div>
            
                <div class="mb-1">
                    <label for="provinsi" class="form-label">Provinsi:</label>
                    <select name="provinsi" id="provinsi" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        <!-- Populate with data dynamically -->
                    </select>
                </div>
            
                <div class="mb-1">
                    <label for="kota" class="form-label">Kabupaten/Kota:</label>
                    <select name="kota" id="kota" class="form-control">
                        <option value="">Pilih Kota</option>
                        <!-- Populate with data dynamically -->
                    </select>
                </div>
            
                <div class="mb-1">
                    <label for="kecamatan" class="form-label">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan" class="form-control">
                        <option value="">Pilih Kecamatan</option>
                        <!-- Populate with data dynamically -->
                    </select>
                </div>
            
                <div class="mb-1">
                    <label for="kelurahan" class="form-label">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan" class="form-control">
                        <option value="">Pilih Kelurahan</option>
                        <!-- Populate with data dynamically -->
                    </select>
                </div>
            
                <div class="mb-2">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>

        <div class="col">
            <h2>Address:</h2>
            @if ($address->isEmpty())
                <div>Belum ada alamat</div>
            @else
                @foreach ($address as $a)
                <div class="col">
                    <div class="row">{{$a->namaDepan}} {{$a->namaBelakang}}</div>
                    <div class="row">{{$a->noHP}}</div>
                    <div class="row">{{$a->detailAlamat}}</div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/provinces.json`)
    .then(response => response.json())
    .then(provinces => 
        {
            var data = provinces;
            var tampung = '<option>Pilih</option>';
            data.forEach(element => {
                tampung += `<option data-reg="${element.id}" value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('provinsi').innerHTML = tampung;
        }
    );

    
</script>
<script>
const selectProvinsi = document.getElementById('provinsi');
selectProvinsi.addEventListener('change',(e)=>{
    var provinsi = e.target.options[e.target.selectedIndex].dataset.reg;
    fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/regencies/${provinsi}.json`)
    .then(response => response.json())
    .then(regencies => {
        var data = regencies;
            var tampung = '<option></option>';
            document.getElementById('kota').innerHTML = '';
            document.getElementById('kecamatan').innerHTML = '';
            document.getElementById('kelurahan').innerHTML = '';
            data.forEach(element => {
                tampung += `<option data-dist="${element.id}" value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('kota').innerHTML = tampung;
    });
});

const selectKota = document.getElementById('kota');
selectKota.addEventListener('change',(e)=>{
    var kota = e.target.options[e.target.selectedIndex].dataset.dist;
    fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/districts/${kota}.json`)
    .then(response => response.json())
    .then(districts =>{
        var data = districts;
            var tampung = '<option></option>';
            document.getElementById('kecamatan').innerHTML = '';
            document.getElementById('kelurahan').innerHTML = '';
            data.forEach(element => {
                tampung += `<option data-vill="${element.id}" value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('kecamatan').innerHTML = tampung;
    });
});

const selectKecamatan = document.getElementById('kecamatan');
selectKecamatan.addEventListener('change',(e)=>{
    var kecamatan = e.target.options[e.target.selectedIndex].dataset.vill;
    fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/villages/${kecamatan}.json`)
    .then(response => response.json())
    .then(village =>{
        var data = village;
            document.getElementById('kelurahan').innerHTML = '';
            var tampung = '<option></option>';
            data.forEach(element => {
                tampung += `<option value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('kelurahan').innerHTML = tampung;
    });
});
</script>
@endsection