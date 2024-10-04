@extends('layouts.appAdmin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
      <div class="card-header"><b>Manage Barang</b></div>
      <div class="card-body">
          <table class="display responsive nowrap" id="tabelTransaksi" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Detail transaksi</th>
                <th>Tanggal Pembelian</th>
                <th data-priority="1">Total Transaksi</th>
                <th>Bukti</th>
                <th data-priority="2">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($transaksi as $htrans)
            <tr>
              <td>{{ $htrans->id }}</td>
              
              <!-- Display transaction details -->
              <td>
                <ul>
                @foreach ($htrans->dtrans as $dtrans)
                  <li>{{ $dtrans->product->namaBarang }} - {{ $dtrans->totalJumlah }} {{ $dtrans->satuanBarang }} x Rp{{ number_format($dtrans->hargaSatuan, 2, ',', '.') }}</li>
                @endforeach
                </ul>
              </td>

              <!-- Display transaction date -->
              <td>{{ $htrans->tanggalPembelian }}</td> 

              <!-- Display total transaction -->
              <td>Rp{{ number_format($htrans->totalPembelian, 2, ',', '.') }}</td>

              <!-- Display transaction proof (bukti) -->
              <td>
                @if ($htrans->buktiPembayaran)
                  <img src="{{ asset('storage/' . $htrans->buktiPembayaran) }}" alt="Bukti" style="width: 100px;">
                @else
                  No proof
                @endif
              </td>

              <!-- Action buttons -->
              <td>
                <div class="row px-2">
                  <a href="#" class="btn btn-primary mb-1">
                    <i class="fa-regular fa-pen-to-square"></i>
                  </a>
                  <a href="#" data-method="DELETE" class="btn btn-danger btn-xs pull-right delete">
                    <i class="fa-regular fa-trash-can"></i>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
            </tbody>
          </table>
      </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#tabelTransaksi').dataTable({
          responsive: true
        });
    });
</script>
@endsection