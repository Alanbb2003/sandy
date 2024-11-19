@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Retur</h2>

    <div class="text-end mb-3">
        <button class="btn btn-outline-warning fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#returnPolicyModal">
            View Return Policy
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelRetur">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Transaction ID</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Tipe</th>
                    <th>Alasan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returns as $retur)
                    <tr>
                        <td>{{ $retur->id }}</td>
                        <td>{{ $retur->htrans->kodeTrans ?? 'Transaction Not Found' }}</td>
                        <td>
                            <div class="text-center">
                                <a href="#" class="openImageModal" data-bs-toggle="modal" data-bs-target="#imageModal" 
                                data-image="{{asset('images/userUpload/' . $retur->fotoBarang)}}" 
                                data-title="{{ $retur->id }}">
                                <img src="{{asset('images/userUpload/' . $retur->fotoBarang) }}" alt="Product Image" style="width: 100px; height: auto;">
                                </a>
                            </div>
                        </td>
                        <td>{{ $retur->dtrans->product->namaBarang ?? 'Product Not Found' }}</td>
                        <td>{{ $retur->jumlahBarangRetur }} {{ $retur->satuanBarangRetur }}</td>
                        <td>
                            {{ 'Rp. ' . number_format($retur->subTotal, 0, ',', '.') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($retur->tanggalRetur )->format('d-m-Y')}}</td>
                        <td>{{$retur->TipePengembalian}}</td>
                        <td>{{ $retur->alasanRetur }}</td>
                        <td>
                            @switch($retur->status)
                                @case(0)
                                    <span class="badge bg-warning text-dark">Pending Confirmation</span>
                                    @break
                                @case(1)
                                    <span class="badge bg-success">Accepted</span>
                                    @break
                                @case(2)
                                    <span class="badge bg-danger">Rejected</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Unknown</span>
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#selectTransactionModal">
        Select Transaction
    </button>

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

    <!-- Transaction Selection Modal -->
    <div class="modal fade" id="selectTransactionModal" tabindex="-1" aria-labelledby="selectTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="selectTransactionModalLabel">Select Transaction for Return</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->kodeTrans }}</td>
                                    <td>{{ \Carbon\Carbon::parse( $transaction->tanggalPembelian)->format('d-m-Y')}}</td>
                                    <td>Rp. {{ number_format($transaction->totalPembelian, 2, ",", ".") }}</td>
                                    <td>
                                        <button class="btn btn-info" onclick="loadTransactionItems({{ $transaction->id }})">Select</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Discount Section -->
                    <div id="transactionDiscount" class="alert alert-success mt-4" style="display: none;">
                        <strong>Discount Applied:</strong> <span id="discountAmount"></span>
                    </div>

                    <!-- Selected Items Table -->
                    <div id="transactionItems" class="mt-4" style="display: none;">
                        <h5>Items in Selected Transaction:</h5>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="transactionItemsBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmSelection" style="display: none;" data-bs-dismiss="modal" onclick="addTransactionItems()">Confirm Selection</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Request Form Modal -->
    <div class="modal fade" id="returnRequestModal" tabindex="-1" aria-labelledby="returnRequestModalLabel" aria-hidden="true">
        <form action="{{ route('retur.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="returnRequestModalLabel">Return Request Details</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="salesHeaderID" id="salesHeaderID">
                        <input type="hidden" name="userID" value="{{ Auth::id() }}">

                        <!-- Selected Items -->
                        <div id="selectedItemsList" class="mb-3"></div>

                        <div class="mb-3">
                            <label for="fotoBarang" class="form-label">Upload Foto Barang</label>
                            <input type="file" class="form-control" id="fotoBarang" name="fotoBarang" disabled required>
                        </div>

                        <div class="mb-3">
                            <label for="alasanRetur" class="form-label">Alasan Pengembalian</label>
                            <textarea class="form-control" name="alasanRetur" id="alasanRetur" rows="3" disabled required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="jumlahBarangRetur" class="form-label">Total Jumlah barang</label>
                            <input type="number" class="form-control" name="jumlahBarangRetur" id="jumlahBarangRetur" disabled readonly>
                        </div>

                        <!-- Return Type -->
                        <div class="mb-3">
                            <label for="returnType" class="form-label">Return Type</label>
                            <select class="form-select" name="returnType" id="returnType" required>
                                <option value="" disabled selected>Select Return Type</option>
                                <option value="Pengembalian Dana">Pengembalian Dana</option>
                                <option value="Pengembalian Barang">Pengembalian Barang</option>
                            </select>
                        </div>

                        <!-- Bank Details -->
                        <div id="bankDetails">
                            <h6 class="text-muted">Bank Details</h6>
                            <div class="mb-3">
                                <label for="bankName" class="form-label">Nama Bank</label>
                                <input type="text" class="form-control" name="bankName" id="bankName">
                            </div>

                            <div class="mb-3">
                                <label for="accountNumber" class="form-label">Nomor Akun</label>
                                <input type="text" class="form-control" name="accountNumber" id="accountNumber" placeholder="e.g., 1234567890 John Doe">
                            </div>
                        </div>

                        <input type="hidden" name="selectedItemsData" id="selectedItemsData">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="submitReturnRequest" disabled>Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--Modal gambar bukti -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Product Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- <script>
     $(document).ready(function() {
        $('#tabelRetur').dataTable({
            responsive: true,
            order: [[0, 'desc']]
        });
    }); 
    function loadTransactionItems(transactionId) {
        $.ajax({
            url: '/get-transaction-items/' + transactionId,
            method: 'GET',
            success: function(data) {
                if (data.dtrans.length === 0) {
                    alert('tidak ada barang dalam transaksi.');
                    return;
                }

                document.getElementById('salesHeaderID').value = transactionId;

                if (data.discount) {
                    $('#discountAmount').text(`Rp. ${parseFloat(data.discount).toLocaleString('id-ID', {minimumFractionDigits: 2})}`);
                    $('#transactionDiscount').show();
                } else {
                    $('#transactionDiscount').hide();
                }

                let itemsHtml = '';
                data.dtrans.forEach(function(item) {
                    itemsHtml += `
                        <tr>
                            <td>${item.product.namaBarang}</td>
                            <td>${item.totalJumlah} ${item.satuanBarang}</td>
                            <td>Rp.${parseFloat(item.hargaSatuan).toLocaleString('id-ID', {minimumFractionDigits: 2})}</td>
                            <td>
                                <input type="radio" name="itemRadio" class="item-radio" value="${item.id}" data-item-name="${item.product.namaBarang}" data-quantity="${item.totalJumlah}" data-price="${item.hargaSatuan}" data-unit="${item.satuanBarang}">
                                <input type="number" class="form-control item-quantity" min="1" max="${item.totalJumlah}" value="1" style="width: 80px; display:inline;" disabled />
                            </td>
                        </tr>
                    `;
                });

                $('#transactionItemsBody').html(itemsHtml);
                $('#transactionItems').show();
                $('#confirmSelection').show();
            },
            error: function() {
                alert('Failed to load transaction items. Please try again.');
            }
        });
    }
    $(document).on('change', '.item-radio', function() {
        $('.item-quantity').prop('disabled', true);
        $(this).closest('tr').find('.item-quantity').prop('disabled', false); 
    });

    function addTransactionItems() {
        const selectedItem = document.querySelector('.item-radio:checked');
        if (!selectedItem) {
            alert('Please select an item.');
            return;
        }

        const itemRow = selectedItem.closest('tr');
        const itemQuantity = itemRow.querySelector('.item-quantity').value;
        const itemName = selectedItem.getAttribute('data-item-name');
        const itemUnit = selectedItem.getAttribute('data-unit');
        const itemPrice = parseFloat(selectedItem.getAttribute('data-price'));

        // Format item details
        const formattedPrice = itemPrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        const selectedItemsHtml = `<p>${itemName} (Jumlah: ${itemQuantity} ${itemUnit}) (Harga per barang: ${formattedPrice})</p>`;

        // Update selected item details in the form
        document.getElementById('selectedItemsList').innerHTML = selectedItemsHtml;
        document.getElementById('selectedItemsData').value = JSON.stringify({
            id: selectedItem.value,
            name: itemName,
            quantity: itemQuantity,
            price: itemPrice,
            unit: itemUnit
        });
        document.getElementById('jumlahBarangRetur').value = itemQuantity;

        // Enable the return form and show the modal
        enableReturnForm();
        const returnRequestModal = new bootstrap.Modal(document.getElementById('returnRequestModal'), { keyboard: false });
        returnRequestModal.show();
    }

    // Enable the Return Form Inputs
    function enableReturnForm() {
        document.getElementById('fotoBarang').disabled = false;
        document.getElementById('alasanRetur').disabled = false;
        document.getElementById('jumlahBarangRetur').disabled = false;
        document.getElementById('submitReturnRequest').disabled = false;
    }

    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');

    document.querySelectorAll('.openImageModal').forEach(item => {
        item.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image');
            const imageTitle = this.getAttribute('data-title');
            
            modalImage.src = imageSrc;
            modalTitle.textContent = "Retur " + imageTitle;
        });
    });

</script> --}}

<script>
    $(document).ready(function () {
    $('#tabelRetur').dataTable({
        responsive: true,
        order: [[0, 'desc']],
    });

    function loadTransactionItems(transactionId) {
        $.ajax({
            url: `/get-transaction-items/${transactionId}`,
            method: 'GET',
            success: function (data) {
                if (data.dtrans.length === 0) {
                    alert('Tidak ada barang dalam transaksi.');
                    return;
                }
                $('#salesHeaderID').val(transactionId);
                if (data.discount) {
                    $('#discountAmount').text(`Rp. ${parseFloat(data.discount).toLocaleString('id-ID', { minimumFractionDigits: 2 })}`);
                    $('#transactionDiscount').show();
                } else {
                    $('#transactionDiscount').hide();
                }
                const itemsHtml = data.dtrans.map(item => `
                    <tr>
                        <td>${item.product.namaBarang}</td>
                        <td>${item.totalJumlah} ${item.satuanBarang}</td>
                        <td>Rp. ${parseFloat(item.hargaSatuan).toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                        <td>
                            <input type="radio" name="itemRadio" class="item-radio" 
                                value="${item.id}" 
                                data-item-name="${item.product.namaBarang}" 
                                data-quantity="${item.totalJumlah}" 
                                data-price="${item.hargaSatuan}" 
                                data-unit="${item.satuanBarang}">
                            <input type="number" class="form-control item-quantity" 
                                min="1" max="${item.totalJumlah}" value="1" 
                                style="width: 80px; display: inline;" disabled>
                        </td>
                    </tr>
                `).join('');
                
                $('#transactionItemsBody').html(itemsHtml);
                $('#transactionItems, #confirmSelection').show();
            },
            error: function () {
                alert('Failed to load transaction items. Please try again.');
            },
        });
    }
    $(document).on('change', '.item-radio', function () {
        $('.item-quantity').prop('disabled', true);
        $(this).closest('tr').find('.item-quantity').prop('disabled', false);
    });

    function addTransactionItems() {
        const selectedItem = $('.item-radio:checked');
        if (!selectedItem.length) {
            alert('Please select an item.');
            return;
        }

        const itemRow = selectedItem.closest('tr');
        const itemQuantity = itemRow.find('.item-quantity').val();
        const itemName = selectedItem.data('item-name');
        const itemUnit = selectedItem.data('unit');
        const itemPrice = parseFloat(selectedItem.data('price'));

        const formattedPrice = itemPrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        const selectedItemsHtml = `<p>${itemName} (Jumlah: ${itemQuantity} ${itemUnit}) (Harga per barang: ${formattedPrice})</p>`;

        $('#selectedItemsList').html(selectedItemsHtml);
        $('#selectedItemsData').val(JSON.stringify({
            id: selectedItem.val(),
            name: itemName,
            quantity: itemQuantity,
            price: itemPrice,
            unit: itemUnit,
        }));
        $('#jumlahBarangRetur').val(itemQuantity);

        enableReturnForm();
        const returnRequestModal = new bootstrap.Modal($('#returnRequestModal')[0], { keyboard: false });
        returnRequestModal.show();
    }

    function enableReturnForm() {
        $('#fotoBarang, #alasanRetur, #jumlahBarangRetur, #submitReturnRequest').prop('disabled', false);
    }

    $(document).on('click', '.openImageModal', function () {
        const imageSrc = $(this).data('image');
        const imageTitle = $(this).data('title');

        $('#modalImage').attr('src', imageSrc);
        $('#imageModalLabel').text(`Retur ${imageTitle}`);
    });

    window.loadTransactionItems = loadTransactionItems;
    window.addTransactionItems = addTransactionItems;
});
</script>
@endsection