
@if ($transaction -> status == 1)

<h1>Hello, {{ $user->name }}!</h1>

<p>Pesanan anda dengan kode <strong>{{ $transaction->kodeTrans }}</strong> sudah diterima, mohon melakukan pembayaran dan mengirim bukti pembayaran di website.</p>

<p><strong>Detail Pesanan:</strong></p>
<ul>
    <li>Tanggal Pembelian: {{ \Carbon\Carbon::parse($transaction->tanggalPembelian)->format('d-m-Y H:i:s') }}</li>
    <li>Total: Rp. {{ number_format($transaction->totalPembelian, 2, ',', '.') }}</li>
</ul>
<p>silahkan melakukan pembayaran ke <strong>BRI 71810 1000 129538 Hansen Bulain/strong> dan mengirim bukti pembayaran melalui menu transaksi di website</p>
<p>Terima kasih sudah berbelanja dengan kami!</p> 

@else

<h1>Hello, {{ $user->name }}!</h1>

<p>Pesanan anda dengan kode <strong>{{ $transaction->kodeTrans }}</strong> sudah diterima.</p>

<p><strong>Detail Pesanan:</strong></p>
<ul>
    <li>Tanggal Pembelian: {{ \Carbon\Carbon::parse($transaction->tanggalPembelian)->format('d-m-Y H:i:s') }}</li>
    <li>Total: Rp. {{ number_format($transaction->totalPembelian, 2, ',', '.') }}</li>
</ul>
<p>Terima kasih sudah berbelanja dengan kami!</p> 

@endif
