@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <a href="{{ url('/address')}}" class="btn btn-info mb-4">Buku Alamat</a>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Profile</h4>
            <div class="text-center mb-4">
                @if($user->picture)
                    <img src="{{ asset('images/photos/' . $user->picture) }}" alt="Profile Picture" class="rounded-circle" width="100" height="100" id="frame">
                @else
                    <i class="fa-solid fa-user fa-lg me-2"></i>
                @endif
            </div>

            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="profilePicture">Upload Profile Picture</label>
                    <input type="file" class="form-control @error('profilePicture') is-invalid @enderror" 
                           id="profilePicture" name="profilePicture" accept="image/*">
                    @error('profilePicture')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('firstName') is-invalid @enderror" id="firstName" name="firstName" value="{{ $user->firstName }}" required placeholder="Nama Depan">
                            <label for="firstName">Nama Depan</label>
                            @error('firstName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('lastName') is-invalid @enderror" id="lastName" name="lastName" value="{{ $user->lastName }}" placeholder="Nama Belakang">
                            <label for="lastName">Nama Belakang</label>
                            @error('lastName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-floating my-3">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ $user->name }}" required placeholder="Username">
                    <label for="username">Username</label>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}" required placeholder="Email">
                    <label for="email">Email</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" 
                           class="form-control @error('phone_number') is-invalid @enderror" 
                           id="phone_number" 
                           name="phone_number" 
                           value="{{ $user->noHp }}" 
                           required 
                           placeholder="Nomor HP" 
                           pattern="^\d{10,15}$"
                           title="Nomor HP harus berupa angka dengan panjang 10-15 karakter">
                    <label for="phone_number">Nomor HP</label>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control @error('tanggalLahir') is-invalid @enderror" id="tanggalLahir" name="tanggalLahir" value="{{ $user->tanggalLahir }}" required placeholder="Tanggal Lahir">
                    <label for="tanggalLahir">Tanggal Lahir (mm/dd/yyyy)</label>
                    @error('tanggalLahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Ubah Profile</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ubah password -->
    <div class="card shadow-sm mt-5">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Ubah Password</h4>
            <form action="{{ route('password.user.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('passwordLama') is-invalid @enderror" id="passwordLama" name="passwordLama" required placeholder="Current Password">
                    <label for="passwordLama">Current Password</label>
                    @error('passwordLama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="New Password">
                    <label for="password">New Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required placeholder="Confirm New Password">
                    <label for="password-confirm">Confirm New Password</label>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$('#profilePicture').change(function preview() {
    frame.src=URL.createObjectURL(event.target.files[0]);
    });
</script>
@endsection