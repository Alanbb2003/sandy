@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <div class="card" style="width: 100%; max-width: 500px;">
        <div class="card-header text-center">
            <h4>Halaman Pembayaran</h4>
        </div>
        <div class="card-body">
            <p><strong>Kode Transaksi:</strong> {{ $newKode }}</p>
            <p><strong>Total Pembayaran:</strong> Rp{{ number_format($totalPayment, 0, ',', '.') }}</p>
            <div class="d-grid gap-2">
                <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
   document.getElementById('pay-button').onclick = function () {
        // Call the Midtrans snap API to open the payment page
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // alert("Payment Success");

                // Send the result to the server to update the payment status
                updatePaymentStatus(result, 'success');
            },
            onPending: function(result) {
                // alert("Payment Pending");

                // Send the result to the server to update the payment status
                updatePaymentStatus(result, 'pending');
            },
            onError: function(result) {
                // alert("Payment Failed");

                // Send the result to the server to update the payment status
                updatePaymentStatus(result, 'failed');
            },
            
        });
    };

    function updatePaymentStatus(result, status) {
        // Send the payment result to the backend to update the transaction status
        $.ajax({
            url: '{{ route('update-payment-status') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                transaction_id: '{{ $newKode }}', // Pass the transaction ID
                status: status
            },
            success: function(response) {
                console.log(response.success);
                // You can redirect the user or handle success responses here
                if (status === 'success') {
                    window.location.href = '/transaction';  // Redirect to a success page
                } else if (status === 'pending') {
                    window.location.href = '/transaction';  // Redirect to a pending page
                } else {
                    window.location.href = '/transaction';  // Redirect to a failed page
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }
</script>
@endsection