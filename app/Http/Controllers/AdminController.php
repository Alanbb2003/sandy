<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class AdminController extends Controller
{
    //
    public function addBarang(Request $request){
        $input = $request->all();
        $this->validate($request,[
            'thumbnail'=>'required|image',
            'inputNamaBarang'=>'required',
            'inputJumlahKecil'=>'required|numeric|min:0|not_in:0',
            'inputSatuanKecil'=>'required'
        ]);
        $barang = Products::orderBy("ID","DESC")->first();

        $files = $request->file('images');
        $thumbnail = $request->file('thumbnail');
        $uploadedFiles = [];

        if ($files) {
            foreach ($files as $key => $file) {

                $fileName = time() . rand(1, 99) . '.' . $file->extension();  
                $filePath = 'images/uploads';
                $file->move(public_path('images/uploads'), $fileName);
                // $file->storeAs('photos', $fileName);

                $uploadedFiles[] = ['name' => $fileName];
            }
        }
        
        // $product = Products::all();
        try {
            $productbaru = new Products();
            // $productbaru->fotoPromosi = $request->thumbnail;
            $productbaru->namaBarang = $request->inputNamaBarang;
            $productbaru->save();
            alert()->success('Success!','Berhasil menambahkan produk');
            return back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addKategori(Request $request){
        $input = $request->all();
        $this->validate($request,[
            'inputkategori'=>'required'
        ]);
        try {
            $kategoribaru = new Category();
            $kategoribaru->nama_category = strtoupper($input["inputkategori"]);
            $kategoribaru->save();
            alert()->success('Success!','Berhasil menambahkan kategori');
            return back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editKategori(Request $request,$id){
        $input = $request->all();

        $Kat = Category::where('ID','=',$id)->first();
        $Kat->nama_category = strtoupper($input["inputkategori"]);
        $Kat->save();

        try {
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
