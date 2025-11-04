@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
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
                    <div class="row g-3 mb-3">
                        <div class="row-md-4">
                            <label class="form-label"><strong>Jumlah Transaksi</strong></label>
                            <div class="input-group">
                                <input type="number" name="minTransactions" id="minTransactions" class="form-control" value="{{ request()->minTransactions }}" placeholder="0">
                                <span class="input-group-text">to</span>
                                <input type="number" name="maxTransactions" id="maxTransactions" class="form-control" value="{{ request()->maxTransactions }}" placeholder="100">
                            </div>
                        </div>
                        <div class="row-md-4">
                            <label class="form-label"><strong>Total Harga Transaksi</strong></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="number" name="minAmount" id="minAmount" class="form-control" value="{{ request()->minAmount }}" placeholder="0">
                                <span class="input-group-text">to</span>
                                <span class="input-group-text">Rp.</span>
                                <input type="number" name="maxAmount" id="maxAmount" class="form-control" value="{{ request()->maxAmount }}" placeholder="1000000">
                            </div>
                        </div>
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
          <div class="card-header"> <b>Manage Pelanggan</b> </div>
          <div class="card-body">
              <table class="table table-striped table-bordered" id="tablePelangganAktif" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Total Transaksi Selesai</th>
                        <th>Tanggal terakhir transaksi dilakukan</th>
                        <th>Total Harga Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customers as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->firstName }} {{ $c->lastName }}</td>
                        <td>{{ $c->noHp }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->total_completed_transactions }}</td>
                        <td>{{\Carbon\Carbon::parse($c->newest_transaction_date)->format('d-m-Y')}}</td>
                        <td>{{ number_format($c->total_transaction_amount, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
          </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
 $(document).ready(function(){
        $('#tablePelangganAktif').dataTable({
            language: {
            emptyTable: "No data available in table"
        },
        responsive: true
        } );
    });
</script>
@endsection