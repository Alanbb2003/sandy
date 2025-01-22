@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Retur</h1>
    
    <div class="row mb-3">
        <div class="col d-flex justify-content-between">
            <a href="#addReturModal" class="btn btn-primary" data-bs-toggle="modal" id="openReturModal">Tambah Retur</a>
            <button class="btn btn-outline-warning fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#returnPolicyModal">
                Lihat Kebijakan Pengembalian
            </button>
        </div>
    </div>
    <table class="display responsive nowrap" id="tabelRetur">
        <thead>
            <tr>
                <th>ID Retur</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Retur</th>
                <th>Jumlah Barang</th>
                <th>Tipe Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returs as $retur)
            <tr>
                <td>{{ $retur->HReturID }}</td>
                <td>{{ $retur->htrans->kodeTrans ?? 'N/A' }}</td>
                <td>{{ $retur->TanggalRetur }}</td>
                <td>{{ $retur->jumlahBarangRetur }}</td>
                <td>
                    {{$retur->TipePengembalian}}
                    @if (!is_null($retur->statusPerubahan))
                        <br>
                        <small class="text-muted">
                            <strong>Perubahan:</strong>
                            @if ($retur->statusPerubahan == 1)
                                Penukaran Barang → Pengembalian Dana
                            @elseif ($retur->statusPerubahan == 2)
                                Pengembalian Dana → Penukaran Barang
                            @endif
                        </small>
                        <br>
                        <small class="text-muted">
                            <strong>Alasan Perubahan:</strong> {{ $retur->AlasanPerubahan }}
                        </small>
                    @endif
                </td>
                <td>
                    @switch($retur->Status)
                    @case(0)
                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                        @break
                    @case(1)
                        <span class="badge bg-success">Diterima</span>
                        @break
                    @case(2)
                        <span class="badge bg-danger">Ditolak</span>
                        @break
                    @case(3)
                        <span class="badge bg-danger">Dibatalkan Pelanggan</span>
                    @break
                    @default
                        <span class="badge bg-secondary">Status Tidak Diketahui</span>
                @endswitch
                </td>
                <td>
                    <button class="btn btn-info" onclick="showDetails({{ $retur->HReturID }})">Detail</button>
                    @if ($retur->Status == 0)
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" 
                            onclick="setCancelReturID({{ $retur->HReturID }})">
                        Batalkan Retur
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan Retur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin membatalkan retur ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmCancelRetur()">Ya, Batalkan</button>
            </div>
        </div>
    </div>
</div>
<!-- Return Policy Modal -->
<div class="modal fade" id="returnPolicyModal" tabindex="-1" aria-labelledby="returnPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="returnPolicyModalLabel">Kebijakan Pengembalian</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alasan Pengembalian</th>
                            <th>Refund</th>
                            <th>Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Produk rusak/cacat</td>
                            <td><i class="fa-solid fa-check"></i></td>
                            <td><i class="fa-solid fa-check"></i></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Spesifikasi produk tidak sesuai yang tertera di website</td>
                            <td><i class="fa-solid fa-check"></i></td>
                            <td><i class="fa-solid fa-check"></i></td>
                        </tr>
                    </tbody>
                </table>

                <p><strong>Ketentuan:</strong></p>
                <p>
                    Rusak adalah cacat produksi dan kerusakan akibat pengiriman dan bukan dari kesalahan penggunaan. 
                    Ketentuan tidak sesuai website adalah produk yang diterima berbeda dengan spesifikasi produk di website 
                    seperti jumlah, tipe, dan ukuran produk. Ketika pengajuan retur diterima, pelanggan dapat melakukan 
                    pengiriman barang yang biaya pengiriman ditanggung sendiri, dan pengiriman ulang akan dilakukan toko 
                    tanpa dikenakan biaya pengiriman.
                    Batas waktu pengajuan adalah 1 minggu setelah pesanan selesai
                </p>

                <p><strong>Perubahan metode pengembalian:</strong></p>
                <p>
                    Toko berhak untuk mengubah metode pengembalian barang ketika barang yang ingin ditukar sedang tidak tersedia.
                </p>

                <p><strong>Metode dan Jangka Waktu Pengembalian Uang:</strong></p>
                <p>
                    Pengembalian uang akan dilakukan melalui transfer bank. Pelanggan akan diminta untuk menginformasikan 
                    nomor rekening bank yang digunakan melalui form di halaman retur. Jangka waktu pengembalian uang maksimal 
                    14 hari kerja setelah pelanggan mendapat email konfirmasi.
                </p>

                <p><strong>Konfirmasi Terhadap Pengajuan Pengembalian Produk:</strong></p>
                <p>
                    Keputusan dan Kebijakan mengenai persetujuan pengembalian produk dan/atau pengembalian uang bersifat mutlak dan tidak dapat diganggu gugat.
                    Toko berhak untuk menolak pengajuan pengembalian Anda jika pengajuan pengembalian tidak sesuai dengan syarat dan ketentuan yang berlaku.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Detail Modal -->
<div class="modal fade" id="returDetailModal" tabindex="-1" aria-labelledby="returDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returDetailModalLabel">Detail Retur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically via JavaScript -->
                <div id="returDetailsContent">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal untuk mengubah tipe pengembalian -->
{{-- <div id="changeReturnTypeModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Tipe Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changeReturnTypeForm">
                    <div class="form-group">
                        <label for="tipePengembalian">Pilih Tipe Pengembalian:</label>
                        <select class="form-select" id="tipePengembalian" name="tipePengembalian" required>
                            <option value="Pengembalian Dana">Pengembalian Dana</option>
                            <option value="Penukaran Barang">Penukaran Barang</option>
                        </select>
                    </div>
                    <input type="hidden" id="returID" name="returID">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submitReturnTypeChange()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div> --}}
<!-- Modal with the DataTable -->
<div class="modal fade" id="addReturModal" tabindex="-1" aria-labelledby="addReturModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Added modal-lg class here -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReturModalLabel">Pilih Transaksi untuk Retur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- DataTable for Completed Transactions -->
                <table id="transactionTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Tanggal Pembelian</th>
                            <th>Total Pembelian</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be injected by JS -->
                    </tbody>
                </table>
                 <!-- Div for displaying Dtrans details -->
                 <div id="dtrans-details-container"></div>
            </div>
        </div>
    </div>
</div>
<script>
    lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true
    })
    $(document).ready(function() {
            $('#tabelRetur').dataTable({
            responsive: true,
            order: [[0, 'desc']]
        });
    });
    
    // function changeReturnType(returID) {
    //     // Mengisi hidden input dengan HReturID untuk dikirim
    //     document.getElementById('returID').value = returID;
        
    //     // Menampilkan modal
    //     var modal = new bootstrap.Modal(document.getElementById('changeReturnTypeModal'));
    //     modal.show();
    // }

    // function submitReturnTypeChange() {
    //     var returID = document.getElementById('returID').value;
    //     var tipePengembalian = document.getElementById('tipePengembalian').value;

    //     // Kirim data ke server untuk mengupdate tipe pengembalian
    //     fetch(`retur/update-return-type/${returID}`, {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //         },
    //         body: JSON.stringify({ tipePengembalian: tipePengembalian })
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {
    //             alert("Tipe pengembalian berhasil diubah.");
    //             location.reload(); // Reload halaman untuk melihat perubahan
    //         } else {
    //             alert("Terjadi kesalahan saat mengubah tipe pengembalian.");
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         alert("Terjadi kesalahan saat mengubah tipe pengembalian.");
    //     });
    // }
    let cancelReturID = null;

// Set the ID of the retur to be canceled
function setCancelReturID(id) {
    cancelReturID = id;
}

// Confirm cancellation
function confirmCancelRetur() {
    if (cancelReturID) {
        // Example AJAX call to cancel the retur
        fetch(`/retur/cancel/${cancelReturID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // alert('Retur berhasil dibatalkan.');
                location.reload();
            } 
            // else {
                // alert('Gagal membatalkan retur.');
            // }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
        });
    }
}
    function showDetails(hreturID) {
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('returDetailModal'));
            modal.show();

            // Display a loading message
            const contentDiv = document.getElementById('returDetailsContent');
            contentDiv.innerHTML = '<p>Loading...</p>';

            // Fetch the retur details from the server
            fetch(`/retur/details/${hreturID}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the modal with the retur details
                    let content = `
                        <h6>ID Retur: ${data.HReturID}</h6>
                        <p><strong>Tanggal Retur:</strong> ${data.TanggalRetur}</p>
                        <p><strong>Jumlah Barang:</strong> ${data.jumlahBarangRetur}</p>
                        <p><strong>Tipe Pengembalian:</strong> ${data.TipePengembalian}</p>
                        <p><strong>Total Retur:</strong> Rp${data.TotalHargaRetur.toLocaleString()}</p>
                        <h6>Detail Barang Retur:</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Alasan</th>
                                    <th>Foto Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    // Loop through the Dretur items to display in the table
                    data.Dretur.forEach(item => {
                        content += `
                            <tr>
                                <td>${item.namaBarang}</td>
                                <td>${item.Jumlah}</td>
                                <td>${item.Satuan}</td>
                                <td>Rp${item.harga.toLocaleString()}</td>
                                <td>Rp${(item.harga * item.Jumlah).toLocaleString()}</td>
                                <td>${item.alasan}</td>
                                <td>
                                    <a href="${item.fotobarang}" data-lightbox="retur-image" data-title="${item.namaBarang}">
                                        <img src="${item.fotobarang}" alt="Barang" class="image-zoom" width="100">
                                    </a>
                                </td>
                            </tr>
                        `;
                    });

                    content += `
                            </tbody>
                        </table>
                    `;

                    contentDiv.innerHTML = content;
                })
                .catch(error => {
                    contentDiv.innerHTML = '<p>Error loading data. Please try again later.</p>';
                    console.error(error);
                });
        }

        document.querySelectorAll('[data-lightbox="retur-image"]').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            // Close the Lightbox modal
            lightbox.close();
        });
    });
  // Open the modal and fetch completed transactions
  document.getElementById('openReturModal').addEventListener('click', function() {
    fetch('/retur/getCompletedTransactions')  // API route to fetch completed transactions
        .then(response => response.json())
        .then(data => {
            // Initialize the DataTable with the data
            $('#transactionTable').DataTable({
                destroy: true, // Ensure the table is re-initialized
                data: data, // Use the fetched data to populate the table
                columns: [
                    { data: 'kodeTrans' },
                    { data: 'tanggalPembelian', render: function(data) {
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    }},
                    { data: 'totalPembelian' },
                    {
                        data: null, // For action column
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-info" onclick="showDtransDetails(${row.id})">Detail</button>
                                <a href="/retur/create/${row.kodeTrans}" class="btn btn-primary">Buat Retur</a>
                            `;
                        }
                    }
                ],
                searching: true, // Enable search functionality
                paging: true,   // Enable pagination
                pageLength: 5,  // Show 5 rows per page
                info: true,     // Show info
                order: [[1, 'desc']] // Order by tanggalPembelian (descending)
            });
        })
        .catch(error => {
            console.error('Error fetching transactions:', error);
        });
});

// Function to show Dtrans details below the table
function showDtransDetails(transactionId) {
    // Clear any existing Dtrans details
    document.getElementById('dtrans-details-container').innerHTML = '';

    // Fetch and display Dtrans details
    fetch(`/retur/getTransactionItems/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            let dtransHTML = `
                <h5>Detail Transaksi:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Foto Produk</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.dtrans.forEach(item => {
                dtransHTML += `
                    <tr>
                        <td><img src="/images/uploads/${item.product.fotoPromosi}" alt="${item.product.namaBarang}" style="max-width: 100px; max-height: 100px;"></td>
                        <td><strong>${item.product.namaBarang}</strong></td>
                        <td><span class="fw-bold">${item.totalJumlah} ${item.satuanBarang}</span></td>
                        <td><span class="fw-bold">Rp${item.hargaSatuan.toLocaleString()}</span></td>
                        <td><span class="fw-bold">Rp${item.subtotal.toLocaleString()}</span></td>
                    </tr>
                `;
            });

            dtransHTML += `
                    </tbody>
                </table>
            `;
            
            // Append Dtrans details below the table
            document.getElementById('dtrans-details-container').innerHTML = dtransHTML;
        })
        .catch(error => {
            console.error('Error fetching Dtrans details:', error);
        });
}
</script>
@endsection