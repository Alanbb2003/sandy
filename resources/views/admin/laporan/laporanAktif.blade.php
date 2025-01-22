@extends('layouts.appAdmin')

@section('nav2')
 @include('admin.partialNav')
@endsection
@section('content')
<h2 class="text-center">Laporan Pelanggan Aktif</h2>
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
                                <input type="number" name="minTransactions" id="minTransactions" class="form-control" value="{{ request()->minTransactions }}" placeholder="Minimum">
                                <span class="input-group-text">to</span>
                                <input type="number" name="maxTransactions" id="maxTransactions" class="form-control" value="{{ request()->maxTransactions }}" placeholder="Maksimum">
                            </div>
                            @error('minTransactions')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('maxTransactions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row-md-4">
                            <label class="form-label"><strong>Total Harga Transaksi</strong></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="number" name="minAmount" id="minAmount" class="form-control" value="{{ request()->minAmount }}" placeholder="minimum">
                                <span class="input-group-text">to</span>
                                <span class="input-group-text">Rp.</span>
                                <input type="number" name="maxAmount" id="maxAmount" class="form-control" value="{{ request()->maxAmount }}" placeholder="maksimum">
                            </div>
                            @error('minAmount')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('maxAmount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
<div class="card container">
    <div class="col-md-12">
        <h4>Top Customer per month</h4>
        <canvas id="topCustomerChart"></canvas>
    </div>
</div>
<div class="card container">
    <div class="row justify-content-center">
        <div class="col-md-11">
          {{-- <div class="card-header"> <b>Manage Pelanggan</b> </div> --}}
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
        responsive: true,
        order: [[6, 'desc']]
        } );
    });
    const ctx = document.getElementById('topCustomerChart').getContext('2d');

// Prepare data by aligning 'data' and 'customers' with the 'labels' array
const labels = @json($chartData['labels']); // ["2024-10", "2024-11", "2024-12", "2025-01"]
const rawData = @json($chartData['data']); // {"2024-10": 11, "2024-11": 11, "2024-12": 10, "2025-01": 4}
const rawCustomers = @json($chartData['customers']); // {"2024-10": "Bayu Saputra", ...}

// Align data and customers with the labels order
const data = labels.map(label => rawData[label]); // [11, 11, 10, 4]
const customers = labels.map(label => rawCustomers[label]); // ["Bayu Saputra", "Dimaz Laksmiwati", "Ami Iswahyudi", "Farhan Pratama"]

const topCustomerChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels, // Month labels
        datasets: [{
            label: 'Jumlah Transaksi Per Bulan',
            data: data, // Aligned transaction counts
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    title: function(tooltipItems) {
                        const index = tooltipItems[0].dataIndex; // Get the data index
                        return `Top Customer: ${customers[index]}`; // Match customer to index
                    },
                    label: function(tooltipItem) {
                        return `Jumlah Transaksi: ${tooltipItem.raw}`; // Match transaction count
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Transaksi'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Bulan'
                }
            }
        }
    }
});
</script>
@endsection