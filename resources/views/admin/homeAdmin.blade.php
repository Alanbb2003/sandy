{{-- @extends('layouts.app') --}}
@extends('layouts.appAdmin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Home</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{ Auth::user()->name }}
                        <br>
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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">Tambah Admin Baru</button>
                <!--Form ubah password!-->
                <hr>
                <h5>Ganti Password</h5>
                <form action="{{ url('/dashboard/changePasswordAdmin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Sekarang</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>

                <hr>
                <h5>Ganti Email</h5>
                <form action="{{route('admin.changeEmail') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="current_email" class="form-label">Email Sekarang</label>
                        <input type="email" name="current_email" id="current_email" class="form-control"
                            value="{{ Auth::user()->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="new_email" class="form-label">Email Baru</label>
                        <input type="email" name="new_email" id="new_email"
                            class="form-control @error('new_email') is-invalid @enderror" required>
                        @error('new_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Ganti Email</button>
                </form>
<br>
 <!-- Delete Admin Form -->
 <h5>Hapus Akun Admin</h5>
 <form id="deleteAdminForm" action="{{ route('admin.delete') }}" method="POST">
     @csrf
     <div class="mb-3">
         <label for="admin_to_delete" class="form-label">Pilih admin untuk dihapus</label>
         <select name="admin_id" id="admin_to_delete" class="form-control" required>
             <option value="" disabled selected>-- Select Admin --</option>
             @foreach ($admins as $admin)
                 @if ($admin->id !== Auth::id())
                     <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                 @endif
             @endforeach
         </select>
     </div>
     <button type="button" class="btn btn-danger" data-bs-toggle="modal"
         data-bs-target="#confirmDeleteAdminModal">Hapus Admin</button>
 </form>

 <!-- Confirm Delete Modal -->
 <div class="modal fade" id="confirmDeleteAdminModal" tabindex="-1" aria-labelledby="confirmDeleteAdminModalLabel"
     aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="confirmDeleteAdminModalLabel">Konfirmasi penghapusan akun admin</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                Apakah yakin melakukan penghapusan akun admin ini, akun akan hilang selamanya.
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                 <button type="submit" form="deleteAdminForm" class="btn btn-danger">Confirm Delete</button>
             </div>
         </div>
     </div>
 </div>

            </div>
        </div>
    </div>
@endsection
