@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card col mx-3">
            <h2>Alamat Baru</h2>
            <form action="{{url('/address/add')}}" method="POST">
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
                    <input id="InputDetail" type="text" name="InputDetail" class="form-control @error('InputDetail') is-invalid @enderror"  value="{{ old('InputDetail') }}" required autocomplete="InputDetail" autofocus placeholder="Detail Alamat">
                    @error('InputDetail')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <div class="mb-1">
                    <label for="InputnoHP" class="form-label">Nomor telefon</label>
                    <input id="InputnoHP" type="text" name="InputnoHP" class="form-control @error('InputnoHP') is-invalid @enderror"  value="{{ old('InputnoHP') }}" required autocomplete="InputnoHP" autofocus placeholder="Nomor telefon">
                    @error('InputnoHP')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-1">
                    <label for="InputKodePos" class="form-label">Kode Pos</label>
                    <input id="InputKodePos" type="text" name="InputKodePos" class="form-control @error('InputKodePos') is-invalid @enderror"  value="{{ old('InputKodePos') }}" required autocomplete="InputKodePos" autofocus placeholder="Kode Pos">
                    @error('InputKodePos')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <div class="mb-1">
                    <label for="provinsi" class="form-label">Provinsi:</label>
                    <select class="form-select" id="provinsi" name="provinsi" placeholder="Pilih Provinsi" >
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
            
                <div class="mb-1">
                    <label for="kota" class="form-label">Kabupaten/Kota:</label>
                    <select name="kota" id="kota" class="form-select" placeholder="Pilih Kabupaten/Kota">
                        <option value="">Pilih Kabupaten/Kota</option>

                    </select>
                </div>
            
                <div class="mb-1">
                    <label for="kecamatan" class="form-label">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan" class="form-select" placeholder="Pilih Kecamatan">
                        <option value="">Pilih Kecamatan</option>

                    </select>
                </div>
            
                <div class="mb-1">
                    <label for="kelurahan" class="form-label">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan" class="form-select" placeholder="Pilih Kelurahan">
                        <option value="">Pilih Kelurahan</option>

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
                <div class="col my-2">
                    <div class="row">{{$a->namaDepan}} {{$a->namaBelakang}}</div>
                    <div class="row">{{$a->noHP}}</div>
                    <div class="row">{{$a->detailAlamat}},{{$a->kodePos}} ,{{$a->provinsi}}, {{$a->kota}}, {{$a->kecamatan}}, {{$a->kelurahan}}</div>

                    <button class="btn btn-info" onclick="editAddress({{ json_encode($a) }})">Edit</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" onclick="setDeleteId({{ $a->id }})">Delete</button>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Modal edit alamat -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAddressModalLabel">Edit Alamat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editAddressForm" action="{{ url('/address/edit') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="addressId" name="addressId">
            
            <div class="mb-3">
              <label for="editNamaDepan" class="form-label">Nama Depan</label>
              <input type="text" class="form-control" id="editNamaDepan" name="editNamaDepan" required>
            </div>
            
            <div class="mb-3">
              <label for="editNamaBelakang" class="form-label">Nama Belakang</label>
              <input type="text" class="form-control" id="editNamaBelakang" name="editNamaBelakang" required>
            </div>
            
            <div class="mb-3">
              <label for="editNoHp" class="form-label">Nomor Telefon</label>
              <input type="text" class="form-control" id="editNoHp" name="editNoHp" required>
            </div>
            
            <div class="mb-1">
                <label for="editDetail" class="form-label">Detail Alamat</label>
                <input id="editDetail" type="text" name="editDetail" class="form-control @error('editDetail') is-invalid @enderror"  value="{{ old('editDetail') }}" required autocomplete="editDetail" autofocus placeholder="Detail Alamat">
                @error('editDetail')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <!-- Provinsi -->
            <div class="mb-3">
              <label for="editProvinsi" class="form-label">Provinsi</label>
              <select class="form-select" id="editProvinsi" name="editProvinsi" required>
                <option>Pilih Provinsi</option>
              </select>
            </div>
            
            <!-- Kota -->
            <div class="mb-3">
              <label for="editKota" class="form-label">Kabupaten/Kota</label>
              <select class="form-select" id="editKota" name="editKota" required>
                <option>Pilih Kota</option>
              </select>
            </div>
            
            <!-- Kecamatan -->
            <div class="mb-3">
              <label for="editKecamatan" class="form-label">Kecamatan</label>
              <select class="form-select" id="editKecamatan" name="editKecamatan" required>
                <option>Pilih Kecamatan</option>
              </select>
            </div>
            
            <!-- Kelurahan -->
            <div class="mb-3">
              <label for="editKelurahan" class="form-label">Kelurahan</label>
              <select class="form-select" id="editKelurahan" name="editKelurahan" required>
                <option>Pilih Kelurahan</option>
              </select>
            </div>
            
            <div class="mb-3">
              <label for="editKodePos" class="form-label">Kode Pos</label>
              <input type="text" class="form-control" id="editKodePos" name="editKodePos" required>
            </div>
  
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- modal hapus alamat -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this address?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteAddressForm" method="POST" action="{{ url('/address/delete') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="addressIdToDelete" name="addressId">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
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
            var tampung = '<option>Pilih Provinsi</option>';
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
<script>
    function editAddress(address) {
    document.getElementById('addressId').value = address.id;
    document.getElementById('editNamaDepan').value = address.namaDepan;
    document.getElementById('editNamaBelakang').value = address.namaBelakang;
    document.getElementById('editNoHp').value = address.noHP;
    document.getElementById('editKodePos').value = address.kodePos;
    document.getElementById('editDetail').value= address.detailAlamat;
    
    var modal = new bootstrap.Modal(document.getElementById('editAddressModal'));
    modal.show();
    }
    function setDeleteId(id) {
        document.getElementById('addressIdToDelete').value = id;
    }
</script>
<script>
    fetch('https://alanbb2003.github.io/api-wilayah-indonesia/api/provinces.json')
    .then(response => response.json())
    .then(provinces => {
        let tampung = '<option>Pilih Provinsi</option>';
        provinces.forEach(element => {
            tampung += `<option data-reg="${element.id}" value="${element.name}">${element.name}</option>`;
        });
        document.getElementById('editProvinsi').innerHTML = tampung;
    });

    document.getElementById('editProvinsi').addEventListener('change', (e) => {
        let provinsiId = e.target.options[e.target.selectedIndex].dataset.reg;
        fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/regencies/${provinsiId}.json`)
        .then(response => response.json())
        .then(regencies => {
            let tampung = '<option>Pilih Kota</option>';
            regencies.forEach(element => {
                tampung += `<option data-dist="${element.id}" value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('editKota').innerHTML = tampung;
            document.getElementById('editKecamatan').innerHTML = '';
            document.getElementById('editKelurahan').innerHTML = '';
        });
    });

    document.getElementById('editKota').addEventListener('change', (e) => {
        let kotaId = e.target.options[e.target.selectedIndex].dataset.dist;
        fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/districts/${kotaId}.json`)
        .then(response => response.json())
        .then(districts => {
            let tampung = '<option>Pilih Kecamatan</option>';
            districts.forEach(element => {
                tampung += `<option data-vill="${element.id}" value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('editKecamatan').innerHTML = tampung;
            document.getElementById('editKelurahan').innerHTML = '';
        });
    });

    document.getElementById('editKecamatan').addEventListener('change', (e) => {
        let kecamatanId = e.target.options[e.target.selectedIndex].dataset.vill;
        fetch(`https://alanbb2003.github.io/api-wilayah-indonesia/api/villages/${kecamatanId}.json`)
        .then(response => response.json())
        .then(villages => {
            let tampung = '<option>Pilih Kelurahan</option>';
            villages.forEach(element => {
                tampung += `<option value="${element.name}">${element.name}</option>`;
            });
            document.getElementById('editKelurahan').innerHTML = tampung;
        });
    });
</script>

@endsection