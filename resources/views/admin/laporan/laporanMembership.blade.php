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
<div class="container my-4">
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
                            <div class="col-md-4">
                                <label for="statusMembership" class="form-label"><strong>Membership Status</strong></label>
                                <select name="statusMembership" id="statusMembership" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="1" {{ request('statusMembership') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('statusMembership') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                           
                            <div class="row-md-4">
                                <label class="form-label"><strong>Start Date</strong></label>
                                <div class="input-group">
                                    <input type="date" name="tanggalMulai_min" class="form-control" placeholder="Start Date" value="{{ request('tanggalMulai_min') }}">
                                    <span class="input-group-text">to</span>
                                    <input type="date" name="tanggalMulai_max" class="form-control" placeholder="End Date" value="{{ request('tanggalMulai_max') }}">
                                </div>
                            </div>

                            <!-- Membership End Date Range -->
                            <div class="row-md-4">
                                <label class="form-label"><strong>End Date</strong></label>
                                <div class="input-group">
                                    <input type="date" name="tanggalAkhir_min" class="form-control" placeholder="Start Date" value="{{ request('tanggalAkhir_min') }}">
                                    <span class="input-group-text">to</span>
                                    <input type="date" name="tanggalAkhir_max" class="form-control" placeholder="End Date" value="{{ request('tanggalAkhir_max') }}">
                                </div>
                            </div>

                            <div class="row-md-4">
                                <label class="form-label"><strong>Point Balance</strong></label>
                                <div class="input-group">
                                    <input type="number" name="saldo_min" class="form-control" placeholder="Min Balance" value="{{ request('saldo_min') }}">
                                    <span class="input-group-text">to</span>
                                    <input type="number" name="saldo_max" class="form-control" placeholder="Max Balance" value="{{ request('saldo_max') }}">
                                </div>
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

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped align-middle" id="tableMembership">
            <thead class="text-center">
                <tr>
                    <th scope="col">Member ID</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Membership Start</th>
                    {{-- <th scope="col">Membership End</th> --}}
                    <th scope="col">Status</th>
                    <th scope="col">Point Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($memberships as $membership)
                    <tr>
                        <td>{{ $membership->memberID }}</td>
                        <td>{{ $membership->user->firstName}} {{ $membership->user->lastName}}</td>
                        <td>{{ \Carbon\Carbon::parse($membership->tanggalMulai)->format('d-m-Y') }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($membership->tanggalAkhir)->format('d-m-Y') }}</td> --}}
                        <td>
                            <span class="badge {{ $membership->statusMembership ? 'bg-success' : 'bg-danger' }}">
                                {{ $membership->statusMembership ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ number_format($membership->points->sum('jumlahPoin')) }}</td>
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
        $('#tableMembership').dataTable({
            language: {
            emptyTable: "No data available in table"
        },
        responsive: true
        } );
    });
</script>
@endsection