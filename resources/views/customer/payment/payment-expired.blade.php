@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <div class="card" style="width: 100%; max-width: 500px;">
        <div class="card-header text-center">
            <h4>Pembayaran Expired</h4>
        </div>
        <div class="card-body text-center">
            <p class="lead">Pembayaran Anda telah Melewati batas waktu.</p>
            <div class="d-grid gap-2">
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
  // You can add any scripts here if necessary
</script>
@endsection