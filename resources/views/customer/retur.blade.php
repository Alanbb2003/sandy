@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Return History</h2>

    <div class="text-end mb-3">
        <button class="btn btn-outline-warning fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#returnPolicyModal">
            View Return Policy
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>Return ID</th>
                    <th>Transaction ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Return Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returns as $retur)
                    <tr>
                        <td>{{ $retur->id }}</td>
                        <td>{{ $retur->htrans->kodeTrans ?? 'Transaction Not Found' }}</td>
                        <td>{{ $retur->dtrans->product->namaBarang ?? 'Product Not Found' }}</td>
                        <td>{{ $retur->jumlahBarangRetur }} {{ $retur->satuanBarangRetur }}</td>
                        <td>{{ $retur->tanggalRetur }}</td>
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

    <!-- Open Transaction Modal Button -->
    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#selectTransactionModal">
        Select Transaction
    </button>

     <!-- Return Policy Modal -->
     <div class="modal fade" id="returnPolicyModal" tabindex="-1" aria-labelledby="returnPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="returnPolicyModalLabel">Return Policy</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Return Policy Overview:</strong></p>
                    <p>Our return policy allows you to return products within 30 days of purchase if they meet the conditions outlined below. Please ensure that:</p>
                    <ul>
                        <li>The product is unused and in its original packaging.</li>
                        <li>The return request includes a valid reason and proof of purchase.</li>
                        <li>The return is requested within the specified timeframe.</li>
                    </ul>
                    <p>For more details, contact our customer service.</p>
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
                                    <td>{{ $transaction->tanggalPembelian }}</td>
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
                            <label for="fotoBarang" class="form-label">Upload Product Photo</label>
                            <input type="file" class="form-control" id="fotoBarang" name="fotoBarang" disabled required>
                        </div>

                        <div class="mb-3">
                            <label for="alasanRetur" class="form-label">Reason for Return</label>
                            <textarea class="form-control" name="alasanRetur" id="alasanRetur" rows="3" disabled required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="jumlahBarangRetur" class="form-label">Total Quantity</label>
                            <input type="number" class="form-control" name="jumlahBarangRetur" id="jumlahBarangRetur" disabled readonly>
                        </div>

                        <!-- Bank Details -->
                        <h6 class="text-muted">Bank Details</h6>
                        <div class="mb-3">
                            <label for="bankName" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bankName" id="bankName" disabled required>
                        </div>
                        <div class="mb-3">
                            <label for="accountNumber" class="form-label">Account Number</label>
                            <input type="text" class="form-control" name="accountNumber" id="accountNumber" placeholder="e.g., 1234567890 John Doe" disabled required>
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
</div>
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

    document.getElementById('selectedItemsData').value = JSON.stringify(selectedItemsData);
    document.getElementById('jumlahBarangRetur').value = itemQuantity;

    enableReturnForm();
    const returnRequestModal = new bootstrap.Modal(document.getElementById('returnRequestModal'), {
        keyboard: false 
    });
    returnRequestModal.show();
}
function enableReturnForm() {
    // Enable all form fields and the submit button
    document.getElementById('fotoBarang').disabled = false;
    document.getElementById('alasanRetur').disabled = false;
    document.getElementById('jumlahBarangRetur').disabled = false;
    document.getElementById('bankName').disabled = false;
    document.getElementById('accountNumber').disabled = false;
    document.getElementById('submitReturnRequest').disabled = false;
}
</script>
@endsection