
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="p-4 bg-light rounded">
                <h2 class="mb-3">Ayo Menjadi Member</h2>
                <p>Nikmati keuntungan eksklusif dengan bergabung dalam program keanggotaan kami.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Earn Points:</strong> 1 point untuk setiap Rp.500 , berlaku selama satu tahun.
                    </li>
                    <li class="list-group-item">
                        <strong>Discounts:</strong> gunakan poin untuk diskon dengan penggunaan minimum 1000 point.
                    </li>
                    <li class="list-group-item">
                        <strong>Email Notifications:</strong> dapatkan informasi terbaru tentang peluncuran produk baru.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="p-4 bg-white rounded border shadow-sm">
                <h3 class="mb-3">Perbarui Tanggal Lahir Anda</h3>
                @if(isset($canJoin) && $canJoin)
                    <form action="{{ route('membership.add') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tanggalLahir" class="form-label">Tangal Lahir</label>
                            <input type="date" class="form-control" name="tanggalLahir" id="tanggalLahir" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Daftar Membership</button>
                    </form>
                @else
                    <p class="text-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Kamu membutuhkan total Rp. 500.000 dalam transaksi yang diselesaikan untuk memenuhi syarat keanggotaan.
                    </p>
                    <div class="form-group">
                        <label for="tanggalLahir" class="form-label">Tangal Lahir</label>
                        <input type="date" class="form-control" disabled>
                    </div>
                    <button class="btn btn-secondary w-100 mt-3" disabled>Daftar Membership</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection