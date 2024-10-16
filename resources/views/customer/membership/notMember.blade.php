
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Become a Member</h2>
    <p>Enjoy exclusive benefits by joining our membership program.</p>
    <ul>
        <li>Exclusive discounts</li>
        <li>Early access to new products</li>
        <li>Loyalty points on every purchase</li>
    </ul>

    <h3>Update Your Birth Date</h3>
    <form action="" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tanggalLahir" class="form-label">Birth Date</label>
            <input type="date" class="form-control" name="tanggalLahir" id="tanggalLahir" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection