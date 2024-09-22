<h2>Thank you for your purchase!</h2>

<p>Your order has been successfully processed. Below are the details:</p>

<p><strong>Transaction ID:</strong> {{ $htrans->id }}</p>
<p><strong>Total Payment:</strong> {{ $htrans->totalPayment }}</p>
<p><strong>Address:</strong> {{ $htrans->address }}</p>

<h3>Items:</h3>
<ul>
    @foreach ($cartItems as $item)
        <li>{{ $item['name'] }} - {{ $item['quantity'] }} x {{ $item['price'] }}</li>
    @endforeach
</ul>

<p>If you have any questions, feel free to contact us!</p>