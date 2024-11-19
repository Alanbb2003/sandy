<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang sisa sedikit</title>
</head>
<body>
    <h1>Stok Barang sisa sedikit</h1>
    <p>Produk di bawah ini memiliki stok sedikit:</p>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kuantitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product as $product)
                <tr>
                    <td>{{ $product->namaBarang }}</td>
                    <td>{{ $product->totalQuantity }}  {{$product->satuanTerkecil}}  
                        @if($product->satuanBesar)
                        / {{ round($product->totalQuantity / $product->isiSatuanBesar) }} {{$product->satuanBesar}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>