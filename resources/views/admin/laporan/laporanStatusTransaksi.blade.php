@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Laporan Status Pemesanan</h2>
   
    
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ url()->current() }}" class="mb-3">
                        <div class="row g-4">
                            
                            <!-- General Filters Column -->
                            <div class="col-md-6">
                                {{-- <h5>General Filters</h5> --}}
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="kodeTrans" class="form-label"><strong>Kode Transaksi</strong></label>
                                        <input type="text" name="kodeTrans" id="kodeTrans" class="form-control" placeholder="Kode Trans" value="{{ request('kodeTrans') }}">
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="namaPembeli" class="form-label"><strong>Nama Pembeli</strong></label>
                                        <input type="text" name="namaPembeli" id="namaPembeli" class="form-control" placeholder="Nama Pembeli" value="{{ request('namaPembeli') }}">
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="alamatPembelian" class="form-label"><strong>Alamat Pembelian</strong></label>
                                        <input type="text" name="alamatPembelian" id="alamatPembelian" class="form-control" placeholder="Alamat Pembelian" value="{{ request('alamatPembelian') }}">
                                    </div>
                
                                    <div class="col-12">
                                        <label for="salesHeaderDate" class="form-label"><strong>Tanggal Pemesanan</strong></label>
                                        <input type="date" name="salesHeaderDate" id="salesHeaderDate" class="form-control" value="{{ request('salesHeaderDate') }}">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Range Filters Column -->
                            <div class="col-md-6">
                                {{-- <h5>Range Filters</h5> --}}
                                <div class="row g-3">
                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Total Harga</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="total_min" class="form-control" placeholder="Min Total" value="{{ request('total_min') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="total_max" class="form-control" placeholder="Max Total" value="{{ request('total_max') }}">
                                        </div>
                                    </div>

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Tanggal Pemesanan</strong></label>
                                        <div class="input-group">
                                            <input type="date" name="salesHeaderDate_start" class="form-control" placeholder="Start Date" value="{{ request('salesHeaderDate_start') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="date" name="salesHeaderDate_end" class="form-control" placeholder="End Date" value="{{ request('salesHeaderDate_end') }}">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="status" class="form-label"><strong>Status</strong></label>
                                        <select name="status" id="status" class="form-select">
                                             <option value="">Semua</option>
                                            <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                            <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Pesanan sedang diproses</option>
                                            <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Pesanan dikirim</option>
                                            <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Pesanan Selesai</option>
                                            <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Pesanan dibatalkan Pembeli</option>
                                            <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Pesanan dibatalkan Penjual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Filter and Reset Buttons -->
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fa-solid fa-filter"></i>    
    </button>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="tablePemesanan">
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th>Kode Trans</th>
                    <th>Nama Pembeli</th>
                    <th>Alamat Pembelian</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td class="text-center">{{ $order->salesHeaderID }}</td>
                    <td class="text-center">{{ $order->kodeTrans }}</td>
                    <td>{{ $order->namaPembeli }}</td>
                    <td>{{ $order->addressSnapshot }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($order->tanggalPembelian)->format('d-m-Y H:i:s') }}</td> 
                    <td>Rp {{ number_format($order->totalPembelian, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @switch($order->status)
                            @case(0)
                                <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                @break
                            @case(1)
                                <span class="badge bg-warning text-dark">Pesanan sedang diproses</span>
                                @break
                            @case(2)
                                <span class="badge bg-warning text-dark">Pesanan dikirim</span>
                                @break
                            @case(3)
                                <span class="badge bg-success">Pesanan Selesai</span>
                                @break
                            @case(4)
                                <span class="badge bg-danger">Pesanan dibatalkan Pembeli.</span>
                                @break
                            @case(5)
                                <span class="badge bg-danger">Pesanan dibatalkan Penjual.</span>
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
</div>
@endsection

@section('script')
<script>
     $(document).ready(function(){
        $('#tablePemesanan').dataTable({
            language: {
            emptyTable: "No data available in table"
        },
        responsive: true
        } );
    });
</script>
@endsection