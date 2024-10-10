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
                <th>Bukti Pembayaran</th>
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
                @switch($k->status)
                    @case(1)
                        <td>Menunggu pembayaran.</td>
                        @break

                    @case(2)
                        <td>The order is being processed.</td>
                        @break

                    @case(3)
                        <td>The order has been completed.</td>
                        @break

                    @case(4)
                        <td>Pesanan dibatalkan.</td>
                        @break
                    @default
                        <td>Unknown order status.</td>
                @endswitch
                <td>
                    @if ($k->buktiPembayaran == null)
                        <button class="btn btn-info">Upload bukti</button>
                    @else
                        <img src="{{ asset('storage/' . $htrans->buktiPembayaran) }}" alt="Bukti" style="width: 100px;">
                    @endif
                </td>
                <td>
                    <a href="" class="btn btn-info my-1">Detail</a>
                    @if ($k->status == 1)
                    <button class="btn btn-danger" onclick="confirmCancel({{ $k->id }})">Batalkan Pemesanan</button>
                    @endif
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
          responsive: true,
          order: [[3, 'desc']]
        } );
    });
    
    function confirmCancel(orderId) {
        if (confirm("Are you sure you want to cancel this order?")) {
            // If the user confirms, send a cancellation request
            window.location.href = "/cancel-order/" + orderId;
        }
    }
</script>
@endsection