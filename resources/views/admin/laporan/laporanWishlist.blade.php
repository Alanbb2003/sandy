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
        <h4>Top 5 Customers by Transaction Amount</h4>
        <!-- Chart Canvas -->
        <canvas id="wishlistChart" width="400" height="200"></canvas>
    </div>
</div>
<div class="card container">
    <div class="row justify-content-center">
        <div class="col-md-11">
          {{-- <div class="card-header"> <b>Manage Pelanggan</b> </div> --}}
          <div class="card-body">
              <table class="table table-striped table-bordered" id="tableWishlist" style="width:100%">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Wishlist Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mostWishlisted as $product)
                    <tr>
                        <td>{{ $product->namaBarang }}</td>
                        <td>{{ $product->category->nama_category }}</td>
                        <td>{{ $product->wishlists_count }}</td>
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
        $('#tableWishlist').dataTable({
            language: {
            emptyTable: "No data available in table"
        },
        responsive: true,
        order: [[2, 'desc']]
        } );
    });
    // Get the data passed from the controller
    const labels = @json($labels);  // Product names
        const wishlistsCount = @json($wishlistsCount);  // Wishlist count

        // Create the chart
        const ctx = document.getElementById('wishlistChart').getContext('2d');
        const wishlistChart = new Chart(ctx, {
            type: 'bar',  // Type of chart (bar chart)
            data: {
                labels: labels,
                datasets: [{
                    label: 'Wishlist Count',
                    data: wishlistsCount,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',  // Bar color
                    borderColor: 'rgba(54, 162, 235, 1)',  // Bar border color
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
</script>
@endsection