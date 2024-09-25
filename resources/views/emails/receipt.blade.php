<h2>Terima kasih atas pembelian Anda!</h2>

<p>Pesanan Anda akan diproses. Berikut adalah detailnya:</p>

<p><strong>ID Transaksi:</strong> {{ $htrans->id }}</p>
<p><strong>Alamat:</strong> {{ $htrans->addressSnapshot }}</p>

<h3>Item:</h3>
<ul>
    @foreach ($cartItems as $item)
        <li>{{ $item['name'] }} - {{ $item['quantity'] }} x {{ $item['price'] }}</li>
    @endforeach
</ul>
<p><strong>Total:</strong> Rp.{{ number_format($htrans->totalPembelian, 2, ",", ".") }}</p>
<p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami!</p>