<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pictures;
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
            'inputSatuanKecil'=>'required',
            'inputHargaKecil'=>'required|numeric|min:0|not_in:0'
        ]);
        $barang = Products::select("ID")->orderBy("ID","DESC")->first();
        // echo $barang;
        $files = $request->file('images');
        $thumbnail = $request->file('thumbnail');
        $uploadedFiles = [];
        if($barang == null){
            $barang = 1;
        }else{
            $barang = $barang->ID + 1;
        }
        // echo $barang;
        $arr = explode(' ',trim($request->inputNamaBarang));
        $item = "";
        if(sizeof($arr) >= 2){
            $first_character = mb_substr($arr[0], 0, 2);
            $second_character = mb_substr($arr[1], 0, 2);
            $item = $first_character.$second_character;
        }else{
            $first_character = mb_substr($request->inputNamaBarang, 0, 2);
            $item = $first_character;
        }
        
        try {
        $thumbnailname = $item.time(). '.' . $thumbnail->extension(); 
        $thumbnail->move(public_path('images/uploads'),$thumbnailname);

        $productbaru = new Products();
        $productbaru->fotoPromosi = $thumbnailname;
        $productbaru->namaBarang = $request->inputNamaBarang;
        $productbaru->fk_kategori = $request->inputKategori;
        $productbaru->deskripsi = $request->inputDeskripsi;
        $productbaru->totalQuantity = $request->inputJumlahKecil;
        $productbaru->satuanTerkecil = strtoupper($request->inputSatuanKecil);
        $productbaru->isiSatuanBesar= $request->inputJumlahBesar;
        $productbaru->satuanBesar = strtoupper($request->inputSatuanBesar);
        $productbaru->hargaKecil = $request->inputHargaKecil;
        $productbaru->hargaBesar = $request->inputHargaBesar;
        $productbaru->Status = 1;
        $productbaru->save();

        if ($files) {
            for ($i=0; $i < count($files); $i++) { 
                $fileName = time() . rand(1, 99) . '.' . $files[$i]->extension();  
                $filePath = 'images/uploads';
                $files[$i]->move(public_path($filePath), $fileName);
                // $file->storeAs('photos', $fileName);

                $uploadedFiles[] = ['name' => $fileName,'path' => $filePath];

                $upload = new Pictures();
                $upload->productID = $barang;
                $upload->fileName = $fileName;
                $upload->filePath = $filePath;
                $upload->save();
            }
        }

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

    public function updateKategori(Request $request)
    {
        try {
            $category = Category::find($request->input('categoryId'));
            if (!$category) {
                return response()->json(['error' => 'Category not found.'], 404);
            }
            $category->nama_category = strtoupper($request->input('categoryName')) ;
            $category->save();
            return response()->json([
                'id' => $category->id,
                'nama_category' => strtoupper($category->nama_category) 
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update category. Please try again.'], 500);
        }
    }

    // public function editKategori(Request $request,$id){
    //     $input = $request->all();

    //     try {
    //         $Kat = Category::where('ID','=',$id)->first();
    //         $Kat->nama_category = strtoupper($input["inputkategori"]);
    //         $Kat->save();
    //         alert()->success('Success!','Berhasil mengubah kategori');
    //         return back();
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }

    public function editBarang(Request $request,$id){
        $barang = Products::where('id','=',$id)->first();
        $this->validate($request,[
            'inputNamaBarang'=>'required',
            'inputjumlahkecil'=>'required'
        ]);
    }

    public function addJumlahBarang(Request $request,$id){
        $barang = Products::where('ID','='<$id)->first();
        $this->validate($request,[
            'inputJumlah'=>'required',
        ]);
        if ($request->satuan == 1) {
            # code.
            $jumlah = $barang->jumlahTerkecil;
        }else{
            $isiBesar = $barang->isiSatuanBesar;
            $tambahBesar = $request->inputJumlah * $isiBesar;
        }
    }
}
