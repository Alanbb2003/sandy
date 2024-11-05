@extends('layouts.appAdmin')

@section('nav2')
<!-- Secondary Navigation Bar -->
<nav class="navbar navbar-expand-md navbar-light bg-light" style="padding: 0.25rem 1rem;">
    <div class="container">
        <div class="d-flex  w-100">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('laporan.stokBarang')}}">Laporan Stok</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('laporan.statusPesanan') }}">Laporan Status Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('laporan.membership') }}">Laporan Membership</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{ url('/profile') }}">Profile</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endsection
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Laporan Stok Barang</h2>
    {{-- <form method="GET" action="{{ url()->current() }}" class="mb-3">
        <div class="row g-4">
            
            <!-- General Filters Column -->
            <div class="col-md-6">
                <h5>General Filters</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label"><strong>Product Name</strong></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" value="{{ request('name') }}">
                    </div>
                    
                    <div class="col-12">
                        <label for="category" class="form-label"><strong>Category</strong></label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="created_at" class="form-label"><strong>Created Date</strong></label>
                        <input type="date" name="created_at" id="created_at" class="form-control" value="{{ request('created_at') }}">
                    </div>
                </div>
            </div>
    
            <!-- Range Filters Column -->
            <div class="col-md-6">
                <h5>Range Filters</h5>
                <div class="row g-3">
                    
                    <!-- Small Unit Price Range -->
                    <div class="col-12">
                        <label class="form-label"><strong>Harga Satuan Kecil</strong></label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="number" name="price_min_small" class="form-control" placeholder="Min Price" value="{{ request('price_min_small') }}">
                            </div>
                            <div class="col">
                                <input type="number" name="price_max_small" class="form-control" placeholder="Max Price" value="{{ request('price_max_small') }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Large Unit Price Range -->
                    <div class="col-12">
                        <label class="form-label"><strong>Harga Satuan Besar</strong></label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="number" name="price_min_big" class="form-control" placeholder="Min Price" value="{{ request('price_min_big') }}">
                            </div>
                            <div class="col">
                                <input type="number" name="price_max_big" class="form-control" placeholder="Max Price" value="{{ request('price_max_big') }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stock Range -->
                    <div class="col-12">
                        <label class="form-label"><strong>Stock Satuan Kecil</strong></label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="number" name="stok_min" class="form-control" placeholder="Min Stock" value="{{ request('stok_min') }}">
                            </div>
                            <div class="col">
                                <input type="number" name="stok_max" class="form-control" placeholder="Max Stock" value="{{ request('stok_max') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label"><strong>Stock Satuan Besar</strong></label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="number" name="stok_min_big" class="form-control" placeholder="Min Stock" value="{{ request('stok_min_big') }}">
                            </div>
                            <div class="col">
                                <input type="number" name="stok_max_big" class="form-control" placeholder="Max Stock" value="{{ request('stok_max_big') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter and Reset Buttons -->
            <div class="col-12 d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form> --}}
    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5>General Filters</h5>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label"><strong>Product Name</strong></label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" value="{{ request('name') }}">
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="category" class="form-label"><strong>Category</strong></label>
                                        <select name="category" id="category" class="form-select">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nama_category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="created_at" class="form-label"><strong>Created Date</strong></label>
                                        <input type="date" name="created_at" id="created_at" class="form-control" value="{{ request('created_at') }}">
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-md-6">
                                <h5>Range Filters</h5>
                                <div class="row g-3">

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Harga Satuan Kecil</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="price_min_small" class="form-control" placeholder="Min Price" value="{{ request('price_min_small') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="price_max_small" class="form-control" placeholder="Max Price" value="{{ request('price_max_small') }}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-12">
                                        <label class="form-label"><strong>Harga Satuan Kecil</strong></label>
                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="number" name="price_min_small" class="form-control" placeholder="Min Price" value="{{ request('price_min_small') }}">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="price_max_small" class="form-control" placeholder="Max Price" value="{{ request('price_max_small') }}">
                                            </div>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Harga Satuan Besar</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="price_min_big" class="form-control" placeholder="Min Price" value="{{ request('price_min_big') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="price_max_big" class="form-control" placeholder="Max Price" value="{{ request('price_max_big') }}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-12">
                                        <label class="form-label"><strong>Harga Satuan Besar</strong></label>
                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="number" name="price_min_big" class="form-control" placeholder="Min Price" value="{{ request('price_min_big') }}">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="price_max_big" class="form-control" placeholder="Max Price" value="{{ request('price_max_big') }}">
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Stock Satuan Kecil</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="stok_min" class="form-control" placeholder="Min Stock" value="{{ request('stok_min') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="stok_max" class="form-control" placeholder="Max Stock" value="{{ request('stok_max') }}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-12">
                                        <label class="form-label"><strong>Stock Satuan Kecil</strong></label>
                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="number" name="stok_min" class="form-control" placeholder="Min Stock" value="{{ request('stok_min') }}">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="stok_max" class="form-control" placeholder="Max Stock" value="{{ request('stok_max') }}">
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row-md-4">
                                        <label class="form-label"><strong>Stock Satuan Besar</strong></label>
                                        <div class="input-group">
                                            <input type="number" name="stok_min_big" class="form-control" placeholder="Min Stock" value="{{ request('stok_min_big') }}">
                                            <span class="input-group-text">to</span>
                                            <input type="number" name="stok_max_big" class="form-control" placeholder="Max Stock" value="{{ request('stok_max_big') }}">
                                        </div>
                                    </div>
                                    {{-- <div class="col-12">
                                        <label class="form-label"><strong>Stock Satuan Besar</strong></label>
                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="number" name="stok_min_big" class="form-control" placeholder="Min Stock" value="{{ request('stok_min_big') }}">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="stok_max_big" class="form-control" placeholder="Max Stock" value="{{ request('stok_max_big') }}">
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fa-solid fa-filter"></i>    
    </button>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="tableStok">
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok (Satuan Kecil)</th>
                    <th>Isi Satuan Besar</th>
                    <th>Stok (Satuan Besar)</th>
                    <th>Harga Satuan Kecil</th>
                    <th>Harga Satuan Besar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td class="text-center">{{ $product->id }}</td>
                    <td>{{ $product->namaBarang }}</td>
                    <td>{{ $product->category->nama_category ?? 'N/A' }}</td>
                    <td class="text-center">{{ $product->totalQuantity }} {{ $product->satuanTerkecil }}</td>
                    <td class="text-center">
                        @if ($product->isiSatuanBesar > 0)
                        {{ $product->isiSatuanBesar }}
                        @else
                         -
                        @endif
                        

                    </td>
                    <td class="text-center">
                        @if($product->isiSatuanBesar > 0)
                            {{ round($product->totalQuantity / $product->isiSatuanBesar) }} {{ $product->satuanBesar }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">Rp {{ number_format($product->hargaKecil, 0, ',', '.') }}</td>
                    <td class="text-end">
                        @if($product->hargaBesar > 0)
                             Rp {{ number_format($product->hargaBesar, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($product->Status == 1)
                            <span class="badge bg-success">Available</span>
                        @else
                            <span class="badge bg-danger">Unavailable</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
     $(document).ready(function(){
        $('#tableStok').dataTable({
            language: {
            emptyTable: "No data available in table"
        },
        responsive: true
        } );
    });
</script>
@endsection