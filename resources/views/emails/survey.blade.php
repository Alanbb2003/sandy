<!DOCTYPE html>
<html>
<head>
    <title>Survey Request</title>
</head>
<body>
    <h1>Terima kasih telah melakukan pembelian dengan kode {{$transaction->kodeTrans}}!</h1>
    <p>Kami harap anda puas dengan produk yang diterima.</p>
    <p>Kami akan senang mendengar tanggapan Anda! Mohon luangkan waktu sejenak untuk mengisi survei kami:</p>
    <a href="{{ $surveyLink }}">{{ $surveyLink }}</a>
</body>
</html>