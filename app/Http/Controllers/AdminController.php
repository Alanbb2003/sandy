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
            'inputNamaBarang'=>'required',
            'inputJumlah'=>'required|numeric|min:0|not_in:0',
            'inputSatuan'=>'required'
        ]);
        $product = Products::all();
        try {
            $productbaru = new Products();
            $productbaru->save();
            alert()->success('Success!','Berhasil menambahkan status');
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
}
