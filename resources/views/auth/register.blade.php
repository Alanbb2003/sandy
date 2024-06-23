@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="vh-100" style="background-color: white;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">

              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img src="{{asset('images/register/6368592.jpg')}}"
                  alt="Register form" class="img-fluid mt-4" style="border-radius: 1rem 0 0 1rem;" />
                  <a href="http://www.freepik.com/author/stories" class="small text-muted ms-5"></a>
              </div>

              <div class="col-md-6 col-lg-7 d-flex align-items-center">

                <div class="card-body p-4 p-lg-5 text-black">

                  <form action="" method="POST">
                    @csrf
                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">Sandy Store</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign up your account</h5>

                    <div class="form-floating mb-4">
                      <input type="text" id="regUser" name="regUser" class="form-control form-control-lg" placeholder="Username"/>
                      <label class="form-label" for="regUser">Username</label>
                    </div>

                    <div class="form-floating mb-4">
                      <input type="text" id="regNama" name="regNama" class="form-control form-control-lg" placeholder="Full Name"/>
                      <label class="form-label" for="regNama">Full Name</label>
                    </div>

                    <div class="form-floating mb-4">
                      <input type="email" id="regEmail" name="regEmail" class="form-control form-control-lg" placeholder="Email"/>
                      <label class="form-label" for="regEmail">Email</label>
                    </div>

                    <div class="form-floating mb-4">
                      <input type="text" id="regTelp" name="regTelp" class="form-control form-control-lg" placeholder="No Telp"/>
                      <label class="form-label" for="regNama">No Telp</label>
                    </div>

                    <div class="form-floating mb-4">
                      <input type="password" id="regPass" name="regPass" class="form-control form-control-lg" placeholder="Password"/>
                      <label class="form-label" for="regPass">Password</label>
                    </div>

                    <div class="form-floating mb-4">
                      <input type="password" id="conPass" name="conPass" class="form-control form-control-lg" placeholder="Confirmation Password" />
                      <label class="form-label" for="conPass">Confirmation Password</label>
                    </div>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" type="submit">Register</button>
                    </div>

                    <p class="mb-5 pb-lg-2" style="color: #393f81;">have an account ? <a href="{{url('/login')}}"
                        style="color: #393f81;">Login here</a></p>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
