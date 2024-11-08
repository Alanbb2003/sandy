@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Laporan Pendapatan</h2>

    <!-- Date Range Filter Form -->
    <form action="{{ url()->current() }}" method="GET" class="text-center mb-4">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" id="startDate" name="startDate" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" id="endDate" name="endDate" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary mt-3">Filter</button>
            </div>
        </div>
    </form>

    <p class="text-center">
        @if ($startDate && $endDate)
            Periode: {{ $startDate }} - {{ $endDate }}
        @else
            Showing All Transactions
        @endif
    </p>

    <!-- Transactions Table -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Transaction ID</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Discount</th>
                <th>Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->kodeTrans }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->tanggalPembelian)->format('d-m-Y') }}</td>
                <td>{{ $transaction->namaPembeli }}</td>
                <td>Rp. {{ number_format($transaction->totalPembelian + $transaction->discount, 2, ",", ".") }}</td>
                <td>Rp. {{ number_format($transaction->discount, 2, ",", ".") }}</td>
                <td>Rp. {{ number_format($transaction->totalPembelian , 2, ",", ".") }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Revenue Summary -->
    <div class="mt-4">
        <h4>Total Gross Revenue: Rp. {{ number_format($grossRevenue, 2, ",", ".") }}</h4>
        <h4>Total Discounts: Rp. {{ number_format($totalDiscount, 2, ",", ".") }}</h4>
        <h4>Total Net Revenue: Rp. {{ number_format($netRevenue, 2, ",", ".") }}</h4>
    </div>
</div>
@endsection