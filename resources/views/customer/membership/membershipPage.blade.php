@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Membership</h1>
<br>
    <h5>Total Point sekarang: {{ $totalPoints }}</h5>
    <h5>Member sejak: {{ \Carbon\Carbon::parse($membership->tanggalDaftar)->format('d/m/Y') }}</h5>
    <h4>Point History</h4>
    <table class="table table-bordered" id="tabelPoin">
        <thead>
            <tr>
                <th>Date</th>
                <th>Points</th>
                <th>Transaction Type</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pointHistory as $point)
            <tr>
                <td>{{ \Carbon\Carbon::parse($point->tanggalPemberianPoin)->format('d/m/Y H:i:s') }}</td>
                <td>{{ $point->jumlahPoin }}</td>
                <td>{{ $point->tipeTransaksi }}</td>
                <td>{{ $point->sumberPoin }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#tabelPoin').dataTable({
          responsive: true,
          pageLength:10,
          order: [[0, 'desc']]
        } );
    });
</script>
@endsection