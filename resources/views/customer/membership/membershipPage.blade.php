@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Membership</h1>
    <h2>Your Membership</h2>
    <p>Welcome, valued member!</p>

    <h3>Total Points: {{ $totalPoints }}</h3>

    <h4>Point History</h4>
    <table class="table table-bordered">
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
                <td>{{ $point->tanggalPemberianPoin }}</td>
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

@endsection