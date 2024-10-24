
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h2>Become a Member</h2>
            <p>Enjoy exclusive benefits by joining our membership program.</p>
            <ul>
                <li>Poin untuk setiap transaksi dengan konversi Rp.500 untuk 1 poin. poin berlaku selama satu tahun</li>
                <li>Diskon produk dengan penggunaan poin untuk pembelanjaan dengan minimal poin 1000</li>
                <li>Notifikasi melalui email mengenai produk baru</li>
                <li>Loyalty points on every purchase</li>
            </ul>
        </div>
        <div class="col">
            <h3>Update Your Birth Date</h3>
            @if(isset($canJoin) && $canJoin)
                <form action="{{ route('membership.add') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="tanggalLahir" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" name="tanggalLahir" id="tanggalLahir" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </form>
            @else
                <p class="text-danger">You do not qualify for membership. Your total completed transactions must be Rp. 500,000 or more to join.</p>
                <form action="#" method="POST" disabled>
                    <div class="mb-3">
                        <label for="tanggalLahir" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" name="tanggalLahir" id="tanggalLahir" required disabled>
                    </div>
                    <button type="submit" class="btn btn-primary" disabled>Daftar</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection