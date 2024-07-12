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
        $barang = Products::orderBy("ID","DESC")->first();
        echo($barang);
        if($request->hasFile('images')){
            $allowedfileExtension=['jpeg','jpg','png'];
            $files = $request->file('photos');
            foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check=in_array($extension,$allowedfileExtension);
                echo($check);
                dd($check);
                echo '<pre>';
                var_dump($check);
                echo '</pre>';
                    // if($check){
                    //     $product= Products::create($request->all());
                    //     foreach ($request->photos as $photo) {
                    //     $filename = $photo->store('photos');
                    //     ItemDetail::create([
                    //     'item_id' => $items->id,
                    //     'filename' => $filename
                    //     ]);}
                    //     echo "Upload Successfully";
                    // }else{
                    // echo '<div class="alert alert-warning"><strong>Warning!</strong> Sorry Only Upload png , jpg </div>';
                    // }
            }
        };
        // $product = Products::all();
        // try {
        //     $productbaru = new Products();
        //     $productbaru->save();
        //     alert()->success('Success!','Berhasil menambahkan status');
            // return back();
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }
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
        $Kat->Nama_status = $request->EditNamaStatus;

        $Kat->save();

        try {
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
