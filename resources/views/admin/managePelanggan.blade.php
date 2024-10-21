{{-- @extends('layouts.app') --}}
@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
          <div class="card-header"> <b>Manage Barang</b> </div>
          <div class="card-body">
              {{-- <table class="display responsive nowrap" id="tablePelanggan" style="width:100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Nomor Telepon</th>
                    <th>Email</th>
                    <th>Tanggal lahir</th>
                    <th>Harga</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($customers as $c)
                <tr>
                  <td>{{$c->id}}</td> 
                  <td>{{$c->firstName}} {{$c->lastName}}</td>
                  <td>{{$c->noHP}}</td>
                  <td>{{$c->email}}</td>
                  <td>{{$c->tanggalLahir}}</td>
                </tr>
                @endforeach
                </tbody>
              </table> --}}
              <table class="table table-striped table-bordered" id="tablePelanggan" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Total Transaksi Selesai</th>
                        <th>Total Jumlah Transaksi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customers as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->firstName }} {{ $c->lastName }}</td>
                        <td>{{ $c->noHp }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->tanggalLahir ? \Carbon\Carbon::parse($c->tanggalLahir)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $c->total_completed_transactions }}</td>
                        <td>{{ number_format($c->total_transaction_amount, 2) }}</td>
                        <td>
                            <a href="" class="btn btn-sm btn-primary">Detail</a>
                        </td>
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
        $('#tablePelanggan').dataTable({
          responsive: true
        } );
    });
</script>
@endsection