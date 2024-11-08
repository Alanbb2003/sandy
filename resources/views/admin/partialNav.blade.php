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
                    <a class="nav-link" href="{{route('laporan.pendapatan') }}">Laporan Pendapatan</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
