<div class="row justify-content-center">
    <div class="col-sm-3 p-1 d-flex flex-row" style="background-color: blue; width: 100%; height: 300px;">
        {{-- @foreach ($kos as $k) --}}
        {{-- <div class="col-md-3 mx-1" style="float:left"> --}}
          @foreach ($barang as $k)
          <div class="card mb-4 p-1 mx-2" style="width: 188px; height: 316;">
            <a target="_blank" href="{{asset('images/uploads/'.$k->fotoPromosi)}}">
              <img class="card-img-top thumbnail" src="{{asset('images/uploads/'.$k->fotoPromosi)}}" alt="Gambar Barang">
            </a>
            <div class="">
              <p class="card-text">Rp.{{$k->hargaKecil}}</p>
              <h5 class="card-title">{{$k->namaBarang}}</h5>
              <a href="{{url('/product/'.$k->slugBarang )}}" class="btn btn-primary nodecor">detail</a>
              {{-- <button  class="btn btn-primary detail-product" data-product-name={{$k->namaBarang}} data-product-id={{$k->id}}>detail</button> --}}
            </div>
          </div>
          @endforeach
           {{-- </div> --}}
        {{-- @endforeach --}}
    </div>
</div>