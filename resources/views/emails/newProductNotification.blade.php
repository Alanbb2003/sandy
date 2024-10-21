<!DOCTYPE html>
<html>
<head>
    <title>New Product Notification</title>
</head>
<body>
    <h1>New Product Available: {{ $product->namaBarang }}</h1>
    <h5>Price:</h5>
    <p>Rp.{{ number_format($product->hargaKecil, 2) }} per 1 {{$product->satuanTerkecil}}</p>
    @if($product->satuanBesar && $product->hargaBesar)
    <p>Rp.{{ number_format($product->hargaBesar, 2) }} per  1 {{$product->satuanBesar}}</p>
    @endif
    <p>Description: {{ $product->description }}</p>

    <p>Check it out on our website!</p>
</body>
</html>