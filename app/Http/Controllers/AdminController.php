<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pictures;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
class AdminController extends Controller
{
    //
    //view
    public function adminHome(){
        return view('admin.homeAdmin',["msg"=>"I am admin role"]);
    }
    
    public function adminManageStock(){
        // $barang = Products::all();
        $barang = Products::join('category','product.fk_kategori','=','category.id')
        ->select('product.*','category.nama_category as category')
        ->get();
        $kategori = Category::all();
        return view('admin.manageStock',compact('barang','kategori'));
    }

    public function adminBarangNew(){
        $kategori = Category::all();
        return view('admin.forms.addStock',compact('kategori'));
    }

    public function getCategories(){
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch categories. Please try again.'], 500);
        }
    }
    
    public function adminMembership(){

        return view('admin.manageMembership');
    }

    public function showeditBarang($id)
    {
        // Fetch the product by ID
        $product = Products::findOrFail($id);

        // Fetch all categories
        $kategori = Category::all();

        $pictures = Pictures::where('productID', $id)->get();
        // Return the edit view with product and categories data
        return view('admin.forms.editStock', compact('product', 'kategori','pictures'));
    }


    //function
    public function deleteImage($id)
    {
       // Find the image by ID in the picture table
        $picture = Pictures::findOrFail($id);

        // Delete the image file from 'images/uploads/' directory
        $filePath = public_path('images/uploads/' . $picture->fileName);
        if (file_exists($filePath)) {
            unlink($filePath);  // Delete the file
        }

        // Delete the image record from the database
        $picture->delete();

        // Redirect back with success message
        return back()->with('success', 'Image deleted successfully!');
    }

    public function addBarang(Request $request){
        $input = $request->all();
        $this->validate($request,[
            'thumbnail'=>'required|image|mimes:jpeg,png,jpg,gif,webp',
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
        $productbaru->slugBarang = Str::slug($request->inputNamaBarang);
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
            $kategoribaru->slugKategori = Str::slug($input["inputkategori"]);
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
            $category->slugKategori = Str::slug($request->input('categoryName'));
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
    public function updateBarang(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            // ... validation rules ...
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $request->validate([
            'inputNamaBarang' => 'required|string|max:255',
            'inputKategori' => 'required|integer',
            'inputDeskripsi' => 'required|string',
            'inputJumlahKecil' => 'required|numeric|min:0',
            'inputSatuanKecil' => 'required|string',
            'inputJumlahBesar' => 'nullable|numeric|min:0',
            'inputSatuanBesar' => 'nullable|string',
            'inputHargaKecil' => 'required|numeric|min:0',
            'inputHargaBesar' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
    
        // Find the product by ID
        $product = Products::findOrFail($id);
    
        // Update product details
        $product->namaBarang = $request->input('inputNamaBarang');
        $product->category_id = $request->input('inputKategori');
        $product->deskripsi = $request->input('inputDeskripsi');
        $product->jumlahKecil = $request->input('inputJumlahKecil');
        $product->satuanTerkecil = $request->input('inputSatuanKecil');
        $product->jumlahBesar = $request->input('inputJumlahBesar');
        $product->satuanTerbesar = $request->input('inputSatuanBesar');
        $product->hargaKecil = $request->input('inputHargaKecil');
        $product->hargaBesar = $request->input('inputHargaBesar');
    
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
        // Check if thumbnail was uploaded
        if ($request->hasFile('thumbnail')) {
            // Check if the product already has a thumbnail
            if ($product->fotoPromosi) {
                // Get the full path of the old thumbnail
                $oldThumbnailPath = public_path('images/uploads/' . basename($product->fotoPromosi));
                
                // Delete the old thumbnail if it exists
                if (file_exists($oldThumbnailPath)) {
                    unlink($oldThumbnailPath);
                }
            }
        
            // Handle file upload for new thumbnail
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = $item . time() . '.' . $thumbnail->extension();
            $thumbnail->move(public_path('images/uploads'), $thumbnailName);
            
            // Save the new thumbnail path to the product
            $thumbnailPath = $request->file('thumbnail')->store('public/products/thumbnails');
            $product->fotoPromosi = $thumbnailName;
        }
    
        // Save the updated product
        $product->save();

        // Check if new images were uploaded for the product
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = time() . rand(1, 99) . '.' . $image->extension();  
                // Store the image in the 'images/uploads/' directory
                $imagePath = $image->storeAs('images/uploads', $fileName, 'public');
    
                // Store image details in the picture table
                Pictures::create([
                    'productID' => $product->id,
                    'fileName' => $fileName,
                    'filePath' => asset('images/uploads/' . $fileName),  // If needed, store the full path
                ]);
            }
        }
    
        // Save and redirect
        return redirect()->route('dashboard.barang.index')
                         ->with('success', 'Product updated successfully!');
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
