{{-- @extends('layouts.app') --}}
@extends('layouts.appAdmin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{ Auth::user()->name }}
                        <br>
                        {{-- {{$msg}} --}}
                        {{ __('You are logged in!') }}
                    </div>
                </div>

                <br>
                <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addAdminModalLabel">Add New Admin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addAdminForm" action="{{ route('admin.add') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="adminName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="adminName" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="adminEmail" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="adminPassword" name="password"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Admin</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">Add New
                    Admin</button>
                <!-- Password Change Form -->
                <hr>
                <h5>Change Password</h5>
                <form action="{{ url('/dashboard/changePasswordAdmin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>

                <hr>
                <h5>Change Email</h5>
                <form action="{{ url('/dashboard/changeEmail') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="current_email" class="form-label">Current Email</label>
                        <input type="email" name="current_email" id="current_email" class="form-control"
                            value="{{ Auth::user()->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="new_email" class="form-label">New Email</label>
                        <input type="email" name="new_email" id="new_email"
                            class="form-control @error('new_email') is-invalid @enderror" required>
                        @error('new_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Change Email</button>
                </form>
            </div>
        </div>
        <div class="footer-main-content">
            <div class="footer-section">
                <div class="row">
                    <div class="col-md-4 col-sm-12 address-section">
                        <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                            <p class="customer-address">Jl. Raya Serpong Km.2 Pakulonan Serpong Tangerang 15325 – Indonesia
                            </p>
                            <p class="customer-service">Hubungi kami jika membutuhkan bantuan atau saran di:</p>
                            <div class="number"><a href="tel:(62-21) 5312-0808">(62-21) 5312-0808</a></div>
                            <div class="whatsapp"><a href="https://wa.me/+6281119099088">081-1190-99088</a></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 menu-section mage-accordion-disabled">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 menu-items accordion-item">
                                <style>
                                    #html-body [data-pb-style=N8SR16R] {
                                        justify-content: flex-start;
                                        display: flex;
                                        flex-direction: column;
                                        background-position: left top;
                                        background-size: cover;
                                        background-repeat: no-repeat;
                                        background-attachment: scroll
                                    }
                                </style>
                                <div data-content-type="row" data-appearance="contained" data-element="main">
                                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}"
                                        data-background-type="image" data-video-loop="true"
                                        data-video-play-only-visible="true" data-video-lazy-load="true"
                                        data-video-fallback-src="" data-element="inner" data-pb-style="N8SR16R">
                                        <div data-content-type="html" data-appearance="default" data-element="main"
                                            data-decoded="true">
                                            <p class="title" tabindex="0">PERUSAHAAN</p>
                                            <ul class="list">
                                                <li><a href="/tentang-kami">Tentang Kami</a></li>
                                                <li><a href="https://corporate.depobangunan.co.id/"
                                                        target="_blank">Korporasi</a></li>
                                                <li><a href="/contact">Hubungi Kami</a></li>
                                                <li><a href="/faq">FAQ</a></li>
                                                <li><a href="/kebijakan-privasi">Kebijakan Privasi</a></li>
                                                <li><a href="/syarat-ketentuan">Syarat dan Ketentuan</a></li>
                                                <li><a href="/karir">Karir</a></li>
                                                <li><a href="/kegiatan-sosial">Kegiatan Sosial</a></li>
                                                <li><a href="/store-locator">Lokasi Toko</a></li>
                                                <li><a href="/blog.html">Blog</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
