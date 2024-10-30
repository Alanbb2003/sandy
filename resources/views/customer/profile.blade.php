@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <a href="{{ url('/address')}}" class="btn btn-info nodecor">Manage Adress</a>

    <h4>Profile</h4>
    <div class="col mt-2">
        <div class="row"> 
            <form action="{{ route('user.update') }}" method="POST">
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
                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->name }}" required autocomplete="username" placeholder="Username">
                    <label for="username" class="form-label">Username</label>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
                    <label for="tanggalLahir" class="form-label">Tanggal Lahir (mm/dd/yyyy)</label>
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

        <h4>Ubah password</h4>
        <div class="row">
            <form action="{{ route('password.user.update') }}" method="POST">
                @csrf
                @method('PUT') <!-- If you're using a PUT request for updates -->
                
                <!-- Old Password -->
                <div class="form-floating mb-4">
                    <input id="passwordLama" type="password" class="form-control @error('passwordLama') is-invalid @enderror" name="passwordLama" required  placeholder="Old Password">
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

</div> --}}

<div class="container mt-5">
    <a href="{{ url('/address')}}" class="btn btn-info mb-4">Manage Address</a>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Profile</h4>
            <form action="{{ route('user.update') }}" method="POST">
                @csrf
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
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ $user->noHp }}" required placeholder="Nomor HP">
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

</script>
@endsection