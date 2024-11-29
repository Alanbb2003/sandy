<?php

namespace App\Http\Controllers;

use App\Mail\CustomerEmail;
use App\Mail\NewProductNotification;
use App\Mail\OrderAcceptedMail;
use App\Models\Category;
use App\Models\Htrans;
use App\Models\Membership;
use App\Models\Pictures;
use App\Models\Point;
use App\Models\Products;
use App\Models\retur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
class AdminController extends Controller
{
    //
    //view
    public function adminHome(){
        $admins = User::where('role', 1)->get();
        return view('admin.homeAdmin',["msg"=>"I am admin role"],compact('admins'));
    }
    
    public function adminManageStock(){
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
        $members = Membership::with(['user', 'points'])->get();
        // $users = User::where('role', '!=', 1)->get(); 
        $users = User::where('role', '!=', 1)
        ->whereNotIn('id', Membership::pluck('fkUserID'))
        ->get();
        
        return view('admin.manageMembership', compact('members', 'users'));
    }

    public function adminTransaksi(){
        $transaksi = Htrans::with(['dtrans.product', 'user'])
                    ->orderBy('id', 'desc')
                    ->get();
        
        return view('admin.manageTransaksi', compact('transaksi'));
    }

    public function showeditBarang($id){
        // Fetch the product by ID
        $product = Products::findOrFail($id);

        // Fetch all categories
        $kategori = Category::all();

        $pictures = Pictures::where('productID', $id)->get();
        // Return the edit view with product and categories data
        return view('admin.forms.editStock', compact('product', 'kategori','pictures'));
    }

    public function adminPelanggan(){
        $customers = User::where('role', '!=', 1)
        ->with(['wishlists.product', 'htrans.dtrans.product.category'])
        ->get()
        ->map(function ($user) {
            $mostBoughtCategory = $user->htrans->where('status', 3)
                ->flatMap->dtrans
                ->groupBy('product.category.id')
                ->sortByDesc(function ($items) {
                    return count($items);
                })
                ->keys()
                ->first();

            $user->most_bought_category = $mostBoughtCategory ? Category::find($mostBoughtCategory) : null;

            $user->total_completed_transactions = $user->htrans->where('status', 3)->count();
            $user->total_transaction_amount = $user->htrans->where('status', 3)->sum('totalPembelian');
            return $user;
        });
        return view('admin.managePelanggan', compact('customers'));
    }

    public function adminRetur(){
        $returRequests = Retur::with(['dtrans.product', 'user'])
        ->get();
        
        return view('admin.manageRetur',compact('returRequests'));
    }


    //function
    
    public function deleteAdmin(Request $request)
    {
        $admin = User::findOrFail($request->admin_id);
    
        if ($admin->id === Auth::id()) {
            alert()->error('error!','Tidak bisa menghapus akun sendiri');
            return back();
        }
    
        $admin->delete();
        alert()->success('Success!','Akun admin berhasil dihapus');
        return back();
    }

    public function deleteImage($id){
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

        
        $files = $request->file('images');
        $thumbnail = $request->file('thumbnail');

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
        DB::beginTransaction();
        try {

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailname = $item . time() . '.webp';
            $thumbnailPath = public_path('images/uploads/' . $thumbnailname);
            $this->convertToWebP($thumbnail, $thumbnailPath);
        }

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

        $files = $request->file('images');
        if ($files) {
            foreach ($files as $file) {
                $fileName = time() . rand(1, 99) . '.webp'; 
                $filePath = 'images/uploads';
                $webpPath = public_path($filePath . '/' . $fileName);
                
                $this->convertToWebP($file, $webpPath);

                // Save image details to the database
                $upload = new Pictures();
                $upload->productID = $productbaru->id;
                $upload->fileName = $fileName;
                $upload->filePath = $filePath;
                $upload->save();
            }
        }
         $members = Membership::with('user')->get();

         foreach ($members as $member) {
             if ($member->user && $member->user->email) {
                 Mail::to($member->user->email)->send(new NewProductNotification($productbaru));
             }
         }
         DB::commit();
        alert()->success('Success!','Berhasil menambahkan barang');
        return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding product: ' . $e->getMessage());
            alert()->error('Error!', 'Gagal menambah barang.');
            return back();
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
            alert()->error('Error!','Gagal menambah kategori');
            return $e->getMessage();
        }
    }

    public function updateKategori(Request $request){
        try {
            $category = Category::find($request->input('categoryId'));
            if (!$category) {
                return response()->json(['error' => 'Category not found.'], 404);
            }
            $category->nama_category = strtoupper($request->input('categoryName')) ;
            $category->slugKategori = Str::slug($request->input('categoryName'));
            $category->save();
            alert()->success('Success!','Berhasil mengubah kategori');
            return response()->json([
                'id' => $category->id,
                'nama_category' => strtoupper($category->nama_category) 
            ]);
        } catch (\Exception $e) {
            alert()->error('Error', 'Terjadi kesalahan saat memperbarui kategori.');
            return response()->json(['error' => 'Failed to update category. Please try again.'], 500);
        }
    }

    public function toggleStatus($id){
        $barang = Products::find($id);  
        if (!$barang) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        $barang->Status = $barang->Status == 1 ? 2 : 1;
        $barang->save();
    
        toast("Berhasil mengubah status produk",'success');
        return redirect()->back()->with('success', 'Product status updated successfully.');
    }

    public function updateBarang(Request $request, $id){
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
        DB::beginTransaction();
        try {
        // Update product details
        $product->namaBarang = $request->input('inputNamaBarang');
        $product->slugBarang = Str::slug($request->input('inputNamaBarang'));
        $product->fk_kategori = $request->input('inputKategori');
        $product->deskripsi = $request->input('inputDeskripsi');
        $product->totalQuantity = $request->input('inputJumlahKecil');
        $product->satuanTerkecil = $request->input('inputSatuanKecil');
        $product->isiSatuanBesar = $request->input('inputJumlahBesar');
        $product->satuanBesar = $request->input('inputSatuanBesar');
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
        if ($request->hasFile('thumbnail')) {
            if ($product->fotoPromosi) {
                $oldThumbnailPath = public_path('images/uploads/' . basename($product->fotoPromosi));
                
                if (file_exists($oldThumbnailPath)) {
                    unlink($oldThumbnailPath);
                }
            }
    
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = $item . time() . '.webp';  
            $thumbnailPath = public_path('images/uploads/' . $thumbnailName);
            $this->convertToWebP($thumbnail, $thumbnailPath);
            $product->fotoPromosi = $thumbnailName;
        }
        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = time() . rand(1, 99) . '.webp'; 

                $imagePath = public_path('images/uploads/' . $fileName);

                $this->convertToWebP($image, $imagePath);

                Pictures::create([
                    'productID' => $product->id,
                    'fileName' => $fileName,
                    'filePath' => asset('images/uploads/' . $fileName), 
                ]);
            }
        }
        DB::commit();
        alert()->success('Success!','Berhasil mengubah barang');
        return redirect('/dashboard/barang');  
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding product: ' . $e->getMessage());
            alert()->error('Error!', 'Something went wrong. Please try again.');
            return back();
        }
    }

    public function acceptTransaction(Request $request) {
        $transactionId = $request->input('transaction_id'); 
        $transaksi = Htrans::with(['dtrans.product', 'user'])->where('id', $transactionId)->firstOrFail();
        
        if ($transaksi->status == 1) {
            $transactionAmount = $transaksi->totalPembelian + $transaksi->discount;
            $pointsEarned = floor($transactionAmount / 500);
            
            $userID = $transaksi->fkUserID;
            $isMember = Membership::where('fkUserID', $userID)->first();
    
            DB::beginTransaction();
            try {   
            if ($isMember) {
                $tanggalPemberian = Carbon::now();
                $tanggalKadaluwarsa = $tanggalPemberian->copy()->addYear(1);
                $tipeTransaksi = 'Purchase';
                $sumberPoin = 'Pembelian dengan jumlah Rp.' . number_format($transactionAmount, 0, ',', '.');
                $saldoPoin = $pointsEarned;
    
                Point::create([
                    'memberID' => $isMember->memberID,
                    'htransID' => $transaksi->id,
                    'tanggalPemberianPoin' => $tanggalPemberian,
                    'jumlahPoin' => $pointsEarned,
                    'tipeTransaksi' => $tipeTransaksi,
                    'sumberPoin' => $sumberPoin,
                    'tanggalKadaluwarsaPoin' => $tanggalKadaluwarsa, 
                    'saldoPoin' => $saldoPoin,
                ]);
            }
            foreach ($transaksi->dtrans as $item) {
                $product = Products::find($item->fkProductID);
                if ($product) {
                    if ($item->satuanBarang == $product->satuanTerkecil) {
                        $product->totalQuantity -= $item->totalJumlah;
                        
                    } else {
                        $reduceBig = $item->totalJumlah * $product->isiSatuanBesar;
                        $product->totalQuantity -= $reduceBig;
                        
                    }
                    $product->save();
                }
            }
                $transaksi->status = 2;
                $transaksi->save();
            
                Mail::to($transaksi->user->email)->send(new OrderAcceptedMail($transaksi->user, $transaksi));
    
                DB::commit();
                alert()->success('Success!', 'Pembayaran dikonfirmasi, stok produk dikurangi, dan poin telah diberikan');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error adding product: ' . $e->getMessage());
                alert()->error('Error!', 'Gagal konfirmasi transaksi');
                return back()->withErrors(['error' => 'Failed to add product']);
            }
        }else{
            $transaksi->status = 3;
            $transaksi->save();
            // Mail::to($transaksi->user->email)->send(new OrderAcceptedMail($transaksi->user, $transaksi));
            alert()->success('Success!', 'Transaksi diterima');
            return redirect()->back();
        }
    }

    public function cancelTransaction(Request $request) {
        $id = $request->input('transaction_idcancel');
        $order = Htrans::find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }
    
        $membership = Membership::where('fkUserID', $order->fkUserID)->first();
        if ($membership) {
            Point::where('htransID', $id)->delete();
        }
    
        $order->status = 5; 
        $order->save();
        $order->alasanBatal = $request->input('inputAlasan');
        alert()->success('Success!', 'Transaksi Ditolak');
        return redirect()->back();
    }

    public function addJumlahBarang(Request $request){
        $barang = Products::where('ID','=',$request->barangId)->first();
        
        $this->validate($request, [
            'amount' => 'required|numeric',
        ]);

        if ($request->satuan == 'kecil') {
            $jumlah = $request->amount;
        }else{
            $isiBesar = $barang->isiSatuanBesar;
            $jumlah = $request->amount * $isiBesar;
        }
        DB::beginTransaction();
        try {
            $barang->totalQuantity += $jumlah;
            $barang->save();
            DB::commit();
            toast("Berhasil menambah jumlah produk",'success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding product: ' . $e->getMessage());
            toast("Gagal menambah jumlah produk",'error');
            return back()->withErrors(['error' => 'Failed to add product']);
        }
    }

    public function confirmRetur(Request $request){
        $retur = Retur::find($request->returID);
        
        if ($retur) {
            $retur->status = 1; 
            $retur->save();
            alert()->success('Success!','Berhasil Konfirmasi retur');
            return redirect()->back();
        }
        alert()->success('error!','Request retur tidak ditemukan');
        return redirect()->back();
    }
    
    public function rejectRetur(Request $request)
    {
        $retur = Retur::find($request->returID);
        
        if ($retur) {
            $retur->status = 2; 
            $retur->save();
            alert()->success('Success!','Berhasil menolak retur');
            return redirect()->back();
        }
        alert()->success('error!','Request retur tidak ditemukan');
        return redirect()->back();
    }

    public function adminAddMembership(Request $request){
        $request->validate([
            'userSelect' => 'required|exists:users,id',
            'tanggalLahir'=>'required'
        ]);

        $userId = $request->input('userSelect');
        $tanggalLahir = $request->input('tanggalLahir');

        $user = User::find($userId);
        if ($user) {
            $user->tanggalLahir = $tanggalLahir;
            $user->save();
        }

        Membership::create([
            'fkUserID' => $userId,
            'tanggalDaftar' => now(),
            'tanggalAkhir' => null,
            'statusMembership' => 1 
        ]);

        return redirect()->back()->with('success', 'Member added and tanggal lahir updated successfully.');
    } 

    public function changePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
    
        $userID = Auth::user()->id;
        $user = User::find($userID); 
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->Save();
            Auth::logout();
            alert()->success('success!','Berhasil mengubah password');
            return redirect()->route('login');
            // return redirect()->back();
        } else {
            alert()->error('error','Current password is incorrect');
            return back();
        }     
    }

    public function addAdmin(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 1, 
        ]);

        if ($admin) {
            alert()->success('success!','Berhasil menambah admin baru');
            return redirect()->back();
        } else {
            alert()->error('error!','Gagal menambah admin baru');
            return redirect()->back();
        }
    }

    public function sendCustomEmail(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        try{
            $recipient = $request->input('recipient');
            $subject = $request->input('subject');
            $messageContent = $request->input('message');

            Mail::to($recipient)->send(new CustomerEmail($subject, $messageContent));

            alert()->success('success!','Berhasil mengirim email');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error sending mail: ' . $e->getMessage());
            toast("Gagal mengirim email",'error');
            return back();
        }
    }
    public function changeEmail(Request $request){
        // Validate the new email
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
        ]);
        $userID = Auth::user()->id;
        $user = User::find($userID); 

        if ($request->new_email === $user->email) {
            toast("email harus berbeda",'error');
            return back();
        }
        $user->email = $request->new_email;
        $user->save();
        toast("Berhasil mengubah email",'success');
        return back();
    }
    /**
     * Convert an image to WebP format using GD library.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $outputWebPPath Path where the WebP image will be saved
     * @return void
     */
    private function convertToWebP($file, $outputWebPPath)
    {
        $extension = $file->extension();
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file->getPathname());
                break;
            case 'png':
                $image = imagecreatefrompng($file->getPathname());
                break;
            case 'gif':
                $image = imagecreatefromgif($file->getPathname());
                break;
            case 'webp':
                $file->move(dirname($outputWebPPath), basename($outputWebPPath));
                return;
            default:
                alert()->error('Error!', 'Unsupported image format');
                return back();
        }

        // Convert to WebP and save
        imagewebp($image, $outputWebPPath, 75); 

        // Free memory
        imagedestroy($image);
    }
}
