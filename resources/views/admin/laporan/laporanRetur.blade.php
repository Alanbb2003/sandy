@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
<h2 class="text-center">Laporan Retur</h2>
<p class="text-center">Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ url()->current() }}" class="p-3">
                    <div class="row-md-3">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="row-md-3">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Diterima</option>
                        <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    </div>
                    
                    <!-- Filter Button -->
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url()->current() }}" class="btn btn-secondary mx-1">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#filterModal">
    <i class="fa-solid fa-filter"></i>    
</button>

<div class="card container px-2 py-2">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Barang</th>
                <th>Gambar</th> 
                <th>Pembeli</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Tanggal request</th>
                <th>Tipe Pengembalian</th>
                <th>Alasan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returns as $retur)
                    <tr>
                        <td>{{ $retur->id }}</td>
                        <td>{{ $retur->dtrans->product->namaBarang }}</td>
                        <td>
                            @if($retur->fotoBarang)
                                <a href="#" class="openImageModal" data-bs-toggle="modal" data-bs-target="#imageModal" 
                                    data-image="{{ asset('images/userUpload/' . $retur->fotoBarang) }}" 
                                    data-title="{{ $retur->dtrans->product->namaBarang }}">
                                    <img src="{{ asset('images/userUpload/' . $retur->fotoBarang) }}" alt="Product Image" style="width: 100px; height: auto;">
                                </a>
                            @else
                                No image available
                            @endif
                        </td>
                        <td>{{ $retur->user->firstName }} {{ $retur->user->lastName }} ({{ $retur->user->email }}) (<strong>{{ $retur->bankName }} {{ $retur->accountNumber }}</strong>)</td>
                        <td>{{ $retur->jumlahBarangRetur }} {{ $retur->satuanBarangRetur }}</td>
                        <td>Rp.{{ number_format($retur->hargaPerBarang, 2) }}</td>
                        <td>Rp.{{ number_format($retur->subTotal, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($retur->tanggalRetur )->format('d-m-Y')}}</td>
                        <td>{{ $retur->TipePengembalian}}</td>
                        <td>{{ $retur->alasanRetur }}</td>
                        <td>
                            @switch($retur->status)
                                @case(0)
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                    @break
                                @case(1)
                                    <span class="badge bg-success">Diterima</span>
                                    @break
                                @case(2)
                                    <span class="badge bg-danger">Ditolak</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Unknown</span>
                            @endswitch
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>

@endsection
@section('script')
<script>

</script>
@endsection