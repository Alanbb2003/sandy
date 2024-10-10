@extends('layouts.app')

@section('content')
<div class="container">
    <button>Manage Adress</button>
    <div class="col">
        <div class="row"> 
            <form action="" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-floating mb-4">
                            <input id="firstName" type="text" class="form-control @error('firstName') is-invalid @enderror" name="firstName" value="{{ $user->firstName }}" required autocomplete="firstName" autofocus placeholder="Nama Depan">
                            <label for="firstName" class="form-label">Nama Depan</label>
                            @error('firstName')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-4">
                            <input id="lastName" type="text" class="form-control @error('lastName') is-invalid @enderror" name="lastName" value="{{ $user->lastName }}"  autofocus placeholder="Nama Belakang">
                            <label for="lastName" class="form-label">nama Belakang</label>
                            @error('lastName')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-floating mb-4">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email }}" required autocomplete="email" placeholder="Email">
                    <label for="email" class="form-label">Email</label>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
    
                <div class="form-floating mb-4">
                    <input id="phone_number" type="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ $user->noHp }}" required autocomplete="phone_number" placeholder="phone_number">
                    <label for="phone_number" class="form-label">Nomor HP</label>
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
    
                <div class="form-floating mb-4">
                    <input id="tanggalLahir" type="date" class="form-control @error('tanggalLahir') is-invalid @enderror" name="tanggalLahir" value="{{$user->tanggalLahir}}" required autocomplete="tanggalLahir" placeholder="tanggalLahir">
                    <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                    @error('tanggalLahir')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
    
                <div class="pt-1 mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Ubah</button>
                </div>
            </form>
        </div>

        <div class="row">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('PUT') <!-- If you're using a PUT request for updates -->
                
                <!-- Old Password -->
                <div class="form-floating mb-4">
                    <input id="passwordLama" type="password" class="form-control @error('passwordLama') is-invalid @enderror" name="passwordLama" required autocomplete="current-password" placeholder="Old Password">
                    <label for="passwordLama" class="form-label">Current Password</label>
                    @error('passwordLama')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <!-- New Password -->
                <div class="form-floating mb-4">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">
                    <label for="password" class="form-label">New Password</label>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <!-- Confirm New Password -->
                <div class="form-floating mb-4">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
                    <label for="password-confirm" class="form-label">Confirm New Password</label>
                </div>
            
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    Change Password
                </button>
            </form>
        </div>
        
    </div>

</div>


{{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}


@endsection

@section('script')
<script>

</script>
@endsection