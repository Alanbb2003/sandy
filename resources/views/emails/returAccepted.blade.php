<!DOCTYPE html>
<html>
<head>
    <title>Permintaan retur diterima</title>
</head>
<body>
    <h1>Permintaan retur diterima</h1>
    <p>Kepada {{ $retur->user->name }},</p>
    <p>Permintaan retur anda telah diterima. jika ingin melakukan pengembalian dana, harap berikan rincian rekening bank Anda sesegera mungkin.</p>
    <p><strong>Detail retur:</strong></p>
    <ul>
        <li>Kode transaksi: {{ $retur->id }}</li>
        <li>Nama Barang:{{ $retur->dtrans->product->namaBarang }}</li>
        <li>Tanggal Retur: {{ \Carbon\Carbon::parse($retur->tanggalRetur)->format('d-m-Y') }}</li>
        <li>Jumlah : {{ $retur->jumlahBarangRetur }} {{ $retur->satuanBarangRetur }}</li>
        <li>Total: Rp. {{ number_format($retur->subtotal, 2, ',', '.') }}</li>
    </ul>
    <p>Silahkan membalas email ini dengan nomor rekening bank anda untuk melakukan pengembalian dana</p>
    <p>Terima kasih telah berbelanja dengan kami!</p>
</body>
</html>