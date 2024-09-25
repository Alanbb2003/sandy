@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transaksi</h2>
    <table class="table" id="tabelTransaksi">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pembeli</th>
                <th>Alamat Pengiriman</th>
                <th>tanggal pembelian</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($htransRecords)
                @foreach ($htransRecords as $k)
                <tr>
                <td>{{$k->id}}</td>
                <td>{{$k->namaPembeli}}</td> 
                <td>{{$k->addressSnapshot}}</td>
                <td>{{$k->tanggalPembelian}}</td> 
                <td>Rp. {{ number_format($k->totalPembelian, 2, ",", ".") }}</td>
                @if ($k->status == 1)
                    <td>
                        Menunggu pembayaran
                    </td>
                @elseif ($k->status == 2)
                    <td>
                        Mengirim barang
                    </td>
                @endif
                <td>
                    <a href="" class="btn btn-info my-1">Detail</a>
                    <button class="btn btn-info">Upload bukti</button>
                </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>


{{-- <button class="btn btn-primary add-to-cart" data-barang-id="{{ $barang->id }}">Add to Cart</button> --}}


@endsection

@section('script')
<script>
  $(document).ready(function(){
        $('#tabelTransaksi').dataTable({
          responsive: true
        } );
    });
</script>
@endsection