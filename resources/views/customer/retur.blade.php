@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Your Return History</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Return ID</th>
                <th>Transaction ID</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Return Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returns as $retur)
                <tr>
                    <td>{{ $retur->id}}</td>
                    <td>{{ $retur->fkHeaderID }}</td>
                    <td>{{ $retur->dtrans->product->namaBarang ?? 'Product Not Found' }}</td>
                    <td>{{ $retur->jumlahBarangRetur }} {{$retur->satuanBarangRetur}}</td>
                    <td>{{ $retur->tanggalRetur }}</td>
                    <td>{{ $retur->alasanRetur }}</td>
                    @switch($retur->status)
                    @case(1)
                        <td>Menunggu Konfirmasi.</td>
                        @break
                    @case(2)
                        <td>Diterima</td>
                        @break
                    @case(3)
                        <td>Ditolak</td>
                        @break
                    @default
                        <td>Unknown retur status.</td>
                @endswitch
                </tr>
            @endforeach
        </tbody>
    </table>

<!-- Button to Open the Return Request Modal -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectTransactionModal">
    Pilih transaksi
</button>

<!-- Transaction Selection Modal -->
<!-- Modal for selecting transaction -->
<div class="modal fade" id="selectTransactionModal" tabindex="-1" aria-labelledby="selectTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectTransactionModalLabel">Select Transaction for Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
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
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->tanggalPembelian }}</td>
                                <td>Rp. {{ number_format($transaction->totalPembelian, 2, ",", ".") }}</td>
                                <td>
                                    <button class="btn btn-info" onclick="loadTransactionItems({{ $transaction->id }})">Select</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Discount section -->
                <div id="transactionDiscount" class="mt-4" style="display: none;">
                    <h5>Discount: <span id="discountAmount"></span></h5>
                </div>

                <div id="transactionItems" class="mt-4" style="display: none;">
                    <h5>Items in Selected Transaction:</h5>
                    <table class="table">
                        <thead>
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
                <button type="button" class="btn btn-primary" id="confirmSelection" style="display: none;" data-bs-dismiss="modal" onclick="addTransactionItems()"> Confirm Selection</button>
            </div>
        </div>
    </div>
</div>

<!-- Return Request Form Modal -->

    <div class="container">
        <form action="{{ route('retur.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnRequestModalLabel">Return Request Details</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="salesHeaderID" id="salesHeaderID">
                    <input type="hidden" name="userID" value="{{ Auth::id() }}">
                    
                    <div id="selectedItemsList" class="mb-3"></div>
        
                    <div class="mb-3">
                        <label for="fotoBarang" class="form-label">Upload Product Photo</label>
                        <input type="file" class="form-control" id="fotoBarang" name="fotoBarang" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="alasanRetur" class="form-label">Reason for Return</label>
                        <textarea class="form-control" name="alasanRetur" id="alasanRetur" rows="3" required></textarea>
                    </div>
        
                    <div class="mb-3">
                        <label for="jumlahBarangRetur" class="form-label">Total Quantity</label>
                        <input type="number" class="form-control" name="jumlahBarangRetur" id="jumlahBarangRetur" readonly>
                    </div>
                    <input type="hidden" name="selectedItemsData" id="selectedItemsData">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Return Request</button>
                </div>
            </div>
        </form>
    </div>
<br>
@endsection

@section('script')

{{-- <script>
    function loadTransactionItems(transactionId) {
    $.ajax({
        url: '/get-transaction-items/' + transactionId,
        method: 'GET',
        success: function(data) {
            if (data.dtrans.length === 0) {
                alert('No items found for this transaction.');
                return;
            }

            // Display discount (if available)
            if (data.discount) {
                $('#discountAmount').text(`Rp. ${parseFloat(data.discount).toLocaleString('id-ID', {minimumFractionDigits: 2})}`);
                $('#transactionDiscount').show();
            } else {
                $('#transactionDiscount').hide();
            }

            // Display transaction items
            let itemsHtml = '';
            data.dtrans.forEach(function(item) {
                itemsHtml += `
                    <tr>
                        <td>${item.product.namaBarang}</td>
                        <td>${item.totalJumlah} ${item.satuanBarang}</td>
                        <td>Rp.${parseFloat(item.hargaSatuan).toLocaleString('id-ID', {minimumFractionDigits: 2})}</td>
                        <td>
                            <input type="checkbox" class="item-checkbox" value="${item.id}" data-item-name="${item.product.namaBarang}" data-quantity="${item.totalJumlah}" data-price="${item.hargaSatuan}" data-unit="${item.satuanBarang}">
                            <input type="number" class="form-control item-quantity" min="1" max="${item.totalJumlah}" value="1" style="width: 80px; display:inline;" />
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

function addTransactionItems() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    let selectedItemsHtml = '';
    let selectedItemsList = []; // To hold selected item details

    selectedItems.forEach(function(item) {
        const itemRow = item.closest('tr'); // Find the parent row of the checkbox
        const itemQuantity = itemRow.querySelector('.item-quantity').value; // Get the quantity input in the same row
        const itemName = item.getAttribute('data-item-name');
        const itemUnit = item.getAttribute('data-unit');
        const itemPrice = item.getAttribute('data-price');

        selectedItemsHtml += `<p>${itemName} (Jumlah: ${itemQuantity} ${itemUnit}) (Harga per barang: ${itemPrice})</p>`;
        selectedItemsList.push({
            id: item.value,
            name: itemName,
            quantity: itemQuantity,
            price: itemPrice
        });
    });

    document.getElementById('s  electedItemsList').innerHTML = selectedItemsHtml;

    // Store selected items data as JSON in a hidden input for form submission
    document.getElementById('selectedItemsData').value = JSON.stringify(selectedItemsList);
}
</script> --}}

<script>
    function loadTransactionItems(transactionId) {
    $.ajax({
        url: '/get-transaction-items/' + transactionId,
        method: 'GET',
        success: function(data) {
            if (data.dtrans.length === 0) {
                alert('tidak ada barang dalam transaksi.');
                return;
            }
            // Set the selected salesHeaderID
            document.getElementById('salesHeaderID').value = transactionId;
            // Display discount (if available)
            if (data.discount) {
                $('#discountAmount').text(`Rp. ${parseFloat(data.discount).toLocaleString('id-ID', {minimumFractionDigits: 2})}`);
                $('#transactionDiscount').show();
            } else {
                $('#transactionDiscount').hide();
            }

            // Display transaction items
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
    $('.item-quantity').prop('disabled', true); // Disable all quantity inputs
    $(this).closest('tr').find('.item-quantity').prop('disabled', false); // Enable the quantity for the selected item
});

function addTransactionItems() {
    const selectedItem = document.querySelector('.item-radio:checked'); // Get the selected item
    if (!selectedItem) {
        alert('Please select an item.');
        return;
    }

    const itemRow = selectedItem.closest('tr');
    const itemQuantity = itemRow.querySelector('.item-quantity').value;
    const itemName = selectedItem.getAttribute('data-item-name');
    const itemUnit = selectedItem.getAttribute('data-unit');
    const itemPrice = selectedItem.getAttribute('data-price');

    const selectedItemsHtml = `<p>${itemName} (Jumlah: ${itemQuantity} ${itemUnit}) (Harga per barang: ${itemPrice})</p>`;
    document.getElementById('selectedItemsList').innerHTML = selectedItemsHtml;

    const selectedItemsData = {
        id: selectedItem.value,
        name: itemName,
        quantity: itemQuantity,
        price: itemPrice,
        unit: itemUnit
    };

    // Store selected item data as JSON in a hidden input for form submission
    document.getElementById('selectedItemsData').value = JSON.stringify(selectedItemsData);
    document.getElementById('jumlahBarangRetur').value = itemQuantity;
}
</script>
@endsection