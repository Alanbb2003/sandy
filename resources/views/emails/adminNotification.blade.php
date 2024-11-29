<h1>Transaksi baru masuk</h1>
<p><strong>Transaction Code:</strong> {{ $htrans->kodeTrans }}</p>
<p><strong>Buyer:</strong> {{ $htrans->namaPembeli }}</p>
<p><strong>Total Payment:</strong> {{ number_format($htrans->totalPembelian, 2) }}</p>
<p><strong>Items:</strong></p>
<table style="width:100%; border-collapse: collapse; border: 1px solid #ddd;">
    <thead>
      <tr style="background-color: #f2f2f2;">
        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Nama Barang</th>
        <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Jumlah</th>
        <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Harga</th>
        <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Subtotal</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($dtransItems as $item)
    <tr>
      <td style="padding: 8px; border: 1px solid #ddd;">{{ $item->namaBarang }}</td>
      <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">{{ $item->totalJumlah }} {{ $item->satuanBarang }}</td>
      <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">Rp {{ number_format($item->hargaSatuan, 0, ',', '.') }}</td>
      <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
      <tr style="background-color: #f2f2f2;">
        <td colspan="3" style="padding: 8px; border: 1px solid #ddd; text-align: right;"><strong>Total:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;"><strong>Rp {{ number_format($htrans->totalPembelian, 2, ',', '.') }}</strong></td>
      </tr>
    </tfoot>
</table>