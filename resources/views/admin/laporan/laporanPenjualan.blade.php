@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
<h2 class="text-center">Laporan Penjualan</h2>
<p class="text-center">Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>

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
                    <div class="row-md-3">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="row-md-3">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
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
    <h3>Top 5 Produk Terlaris</h3>
    <canvas id="topProductsChart"></canvas>
</div>

<div class="card container px-2 py-2">
    <table class="table table-bordered" id="tabelPenjualan">
        <thead class="table-light">
            <tr>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Total Quantity Sold</th>
                <th>Total Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesPerProduct as $productName => $units)
                <tr>
                    <td rowspan="{{ count($units) }}" class="align-middle">{{ $productName }}</td>
                    @foreach ($units as $index => $unit)
                        @if ($index > 0) <tr> @endif
                        <td>{{ $unit->unit }}</td>
                        <td>{{ $unit->total_quantity_sold }}</td>
                        <td>{{ number_format($unit->total_income, 2) }}</td>
                        @if ($index > 0) </tr> @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
    $('#tabelPenjualan').dataTable({
        responsive: true,
        order: [[0, 'desc']]
    });});

    const salesData = @json($chartData);


    const labels = salesData.map(item => item.product.namaBarang);
    const data = salesData.map(item => item.total_quantity_sold);

    const ctx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labels, 
            datasets: [{
                label: 'Jumlah Terjual',
                data: data, 
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Terjual'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Produk'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Top 5 Produk Terjual'
                }
            }
        }
    });
</script>
@endsection