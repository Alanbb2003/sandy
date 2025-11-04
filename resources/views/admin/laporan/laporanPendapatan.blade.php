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
                <input type="date" id="startDate" name="startDate" class="form-control" value="{{  request('startDate') }}">
            </div>
            <div class="col-md-3">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" id="endDate" name="endDate" class="form-control" value="{{request('endDate') }}">
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary mt-3">Reset</a>
            </div>
        </div>
    </form>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card container my-2">
    <h3>Total Pendapatan per Bulan</h3>
    <canvas id="monthlyRevenueChart"></canvas>
</div>
<div class="card container px-2 py-2">
    <p class="text-center">
        @if ($startDate && $endDate)
            Periode: {{ $startDate }} - {{ $endDate }}
        @else
            Showing All Transactions
        @endif
    </p>

    <!-- Transactions Table -->
    <table class="table table-bordered" id="tabelPendapatan">
        <thead class="table-light">
            <tr>
                <th>Transaction ID</th>
                <th>Date</th>
                {{-- <th>Customer Name</th> --}}
                <th>Total Amount</th>
                <th>Discount</th>
                <th>Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->kodeTrans }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->tanggalPembelian)->format('d/m/Y') }}</td>
                <td>{{ number_format($transaction->totalPembelian) }}</td>
                <td>{{ number_format($transaction->discount) }}</td>
                <td>{{ number_format($transaction->totalPembelian + $transaction->discount) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Revenue Summary -->
    <div class="mt-4">
        <div class="mt-4">
            <h4>Total Gross Revenue: Rp. {{ number_format($grossRevenue, 2, ",", ".") }}</h4>
            <h4>Total Discounts: Rp. {{ number_format($totalDiscount, 2, ",", ".") }}</h4>
            <h4>Saldo Kredit: Rp. {{ number_format($saldoKredit, 2, ",", ".") }}</h4>
            <h4>Saldo Debit: Rp. {{ number_format($saldoDebit, 2, ",", ".") }}</h4>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
     $(document).ready(function(){
        $('#tabelPendapatan').dataTable({
            language: {
            emptyTable: "No data available in table"
        },
        responsive: true
        } );
    });
    // Data dari controller (Laravel Blade Syntax)
    const monthlyRevenue = @json($monthlyRevenue);
const saldoKredit = @json($saldoKredit);
const saldoDebit = @json($saldoDebit);

// Extract labels (bulan) and data
const labels = Object.keys(monthlyRevenue);
const revenueData = Object.values(monthlyRevenue);

// Chart.js configuration
const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line', // Line chart
    data: {
        labels: labels, // Months (X-axis)
        datasets: [
            {
                label: 'Gross Revenue',
                data: revenueData, // Monthly revenue (Y-axis)
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: 'Saldo Kredit',
                data: Array(labels.length).fill(saldoKredit), // Display a flat line for saldoKredit
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: false,
                borderWidth: 2,
                borderDash: [5, 5] // Dashed line for credit
            },
            {
                label: 'Saldo Debit',
                data: Array(labels.length).fill(saldoDebit), // Display a flat line for saldoDebit
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: false,
                borderWidth: 2,
                borderDash: [5, 5] // Dashed line for debit
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Pendapatan (IDR)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Bulan'
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Total Pendapatan per Bulan'
            }
        }
    }
});
</script>
@endsection