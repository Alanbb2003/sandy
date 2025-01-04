<h1>Halo, {{ $user->name }}!</h1>

<p>Pesanan anda dengan kode <strong>{{ $transaction->kodeTrans }}</strong> sudah diterima, dan akan dikirim.</p>

<p><strong>Detail Pesanan:</strong></p>
<ul>
    <li>Tanggal Pembelian: {{ \Carbon\Carbon::parse($transaction->tanggalPembelian)->format('d-m-Y H:i:s') }}</li>
    <li>Total: Rp. {{ number_format($transaction->totalPembelian, 2, ',', '.') }}</li>
</ul>
<p>Terima kasih sudah berbelanja dengan kami!</p> 

