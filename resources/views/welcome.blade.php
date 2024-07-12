@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-3 p-1 d-flex flex-row" style="background-color: blue;">
          {{-- <div class="row text-center"> --}}
            {{-- @foreach ($kos as $k) --}}
            {{-- <div class="col-md-3 mx-1" style="float:left"> --}}
              <div class="card mb-4">
                <img class="card-img-top" src="{{asset('images/dev/login.jpg')}}" alt="">
                {{-- <img class="card-img-top"
                     src="{{ asset("storage/gambar/".$k->kos_gambar) }}" alt="Card image cap"> --}}
                <div class="card-body">
                  <p class="card-text">harga</p>
                  <h5 class="card-title">nama barang</h5>
                  <a class="btn btn-primary" href="">Detail</a>
                </div>
              </div>

              <div class="card mb-4">
                <img class="card-img-top" src="{{asset('images/dev/login.jpg')}}" alt="">
                {{-- <img class="card-img-top"
                     src="{{ asset("storage/gambar/".$k->kos_gambar) }}" alt="Card image cap"> --}}
                <div class="card-body">
                  <p class="card-text">harga</p>
                  <h5 class="card-title">nama barang</h5>
                  <a class="btn btn-primary" href="">Detail</a>
                </div>
              </div>
            {{-- </div> --}}
            {{-- @endforeach --}}
            {{-- </div> --}}
        </div>
    </div>



    
    <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
        <div class="col mb-3">
          <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
          </a>
          <p class="text-body-secondary">&copy; 2024</p>
        </div>
    
        <div class="col mb-3">
          <h5>Section</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Features</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Pricing</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">About</a></li>
          </ul>
        </div>
    
        <div class="col mb-3">
          <h5>Section</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Features</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Pricing</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">About</a></li>
          </ul>
        </div>
      </footer>
</div>

@endsection
