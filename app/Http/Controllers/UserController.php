<?php

namespace App\Http\Controllers;

use App\Mail\ReceiptMail;
use App\Models\Alamat;
use App\Models\Category;
use App\Models\Dtrans;
use App\Models\Htrans;
use App\Models\Membership;
use App\Models\Pictures;
use App\Models\Point;
use App\Models\Products;
use App\Models\retur;
use App\Models\User;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    // view 
    public function checkoutPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();

        $currentPoints = 0;
        $memberstatus = Membership::where('fkUserID','=',$userID)->first();
        if($memberstatus){
            $pointRecord = Point::where('tanggalKadaluwarsaPoin', '>', now())->orWhereNull('tanggalKadaluwarsaPoin')->get();
            foreach ($pointRecord as $point) { 
                if ($point->jumlahPoin < 0) {
                    $currentPoints = 0;
                } else {
                    $currentPoints += $point->jumlahPoin;
                }
            }
        }
        $cart = session()->get('cart', []);
        $totalAmmount = 0;

        foreach ($cart as $item) {
            $totalAmmount += $item['price'] * $item['quantity'];
        }
        $pointsEarned = floor($totalAmmount / 500);
        return view('customer.checkout', compact('address','totalAmmount','memberstatus','currentPoints','pointsEarned'));
    }

    public function addressPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();
        return view('customer.adressInput',compact('address'));
    }

    // public function membershipPage(){
    //     $userId = auth()->user()->id;
    //     $membership = Membership::where('fkUserID', $userId)->first();
    //     $totalPoints = 0;
    //     if ($membership) {
    //         $memberstatus = Membership::where('fkUserID','=', $userId)->first();
    //         if($memberstatus){
    //             $pointRecord = Point::where('memberID', $membership->memberID)
    //                 ->where(function ($query) {
    //                     $query->where('tanggalKadaluwarsaPoin', '>', now())
    //                         ->orWhereNull('tanggalKadaluwarsaPoin');
    //                 })
    //                 ->get();
    //             foreach ($pointRecord as $point) { 
    //                 if ($point->jumlahPoin < 0) {
    //                     $totalPoints = 0;
    //                 } else {
    //                     $totalPoints += $point->jumlahPoin;
    //                 }
    //             }
    //         }

    //         $pointHistory = Point::where('memberID', $membership->memberID)->get();
            
    //         return view('customer.membership.membershipPage', compact('totalPoints', 'pointHistory'));
    //     } else {
    //         return view('customer.membership.notMember');
    //     }
    // }

    public function membershipPage(){
        $userId = auth()->user()->id;
        $membership = Membership::where('fkUserID', $userId)->first();
        $totalPoints = 0;
        $totalTransactionAmount = Htrans::where('fkUserID', $userId)
            ->where('status', 3) // Assuming status 3 indicates a completed transaction
            ->sum('totalPembelian'); // Replace 'totalAmount' with the actual column name for transaction amount
    
        if ($membership) {
            $memberstatus = Membership::where('fkUserID','=', $userId)->first();
            if($memberstatus){
                $pointRecord = Point::where('memberID', $membership->memberID)
                    ->where(function ($query) {
                        $query->where('tanggalKadaluwarsaPoin', '>', now())
                            ->orWhereNull('tanggalKadaluwarsaPoin');
                    })
                    ->get();
                foreach ($pointRecord as $point) { 
                    if ($point->jumlahPoin < 0) {
                        $totalPoints = 0;
                    } else {
                        $totalPoints += $point->jumlahPoin;
                    }
                }
            }
    
            $pointHistory = Point::where('memberID', $membership->memberID)->get();
            
            return view('customer.membership.membershipPage', compact('totalPoints', 'pointHistory', 'totalTransactionAmount'));
        } else {
            // If not a member, check if the total transaction amount is enough
            if ($totalTransactionAmount >= 500000) {
                return view('customer.membership.notMember', compact('totalTransactionAmount'))->with('canJoin', true);
            } else {
                return view('customer.membership.notMember', compact('totalTransactionAmount'))->with('canJoin', false);
            }
        }
    }

    public function transactionPage(){
        $userId = Auth::user()->id; 
        
        $htransRecords = Htrans::with('dtrans.product')
        ->where('fkUserID', $userId) 
        ->orderBy('id', 'desc') 
        ->get();

        if (!$htransRecords) {
            return response()->json(['error' => 'Transaction not found or unauthorized'], 404);
        }
        return view('customer.transaction',compact('htransRecords'));
    }

    public function wishlistPage(){
        $userID = Auth::user()->id;
        $WishlistItems =  Wishlist::where('fkUserID', $userID)
        ->with('product')
        ->get();
        return view('customer.wishlist',compact('WishlistItems'));
    }

    public function profilePage(){
        $userID = Auth::user()->id;
        $user = User::find($userID);
        return view('customer.profile',compact('user'));
    }

    public function showReturnHistory()
    {
        // $returns = retur::where('fkUserID', auth()->id())->get();
        $returns = retur::with(['dtrans.product'])->where('fkUserID', auth()->id())->get();
        $transactions = Htrans::with(['dtrans.product'])->where('fkUserID', auth()->id())
        ->where('tanggalPembelian', '>=', now()->subWeeks(2))
        ->get();

        return view('customer.retur', compact('returns', 'transactions'));
    }

    public function getTransactionItems($id){     
        $transaction = Htrans::with('dtrans.product') 
        ->find($id);

        if (!$transaction) {
            return response()->json([], 404); 
        }

        return response()->json([
            'dtrans' => $transaction->dtrans,
            'discount' => $transaction->discount, 
        ]);
    }

    // function
    public function addAlamat(Request $request){
        $userID = Auth::user()->id;

        $this->validate($request, [
            'InputnamaDepan' => 'required',
            'InputnamaBelakang' => 'required',
            'InputDetail' => 'required',
            'provinsi' => 'required',
            'InputKodePos' => 'required',
            'InputnoHP' => 'required',
        ]);

        
        try {
            Alamat::create([
                'fkUserID' => $userID,
                'namaDepan' => $request->InputnamaDepan,
                'namaBelakang' => $request->InputnamaBelakang,
                'noHP' => $request->InputnoHP,
                'provinsi' => $request->provinsi,
                'kota' => $request->kota,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'kodePos' => $request->InputKodePos,
                'detailAlamat' => $request->InputDetail,
            ]);
    
            alert()->success('Success!', 'Berhasil menambahkan alamat');
            return redirect('/checkout');
    
        } catch (\Exception $e) {
            abort(500, 'Error while adding address');
        }
    }

    public function updateAddress(Request $request) {
        $address = Alamat::find($request->addressId);

        $this->validate($request, [
            'editNamaDepan' => 'required',
            'editNamaBelakang' => 'required',
            'editNoHp' => 'required',
            'editProvinsi' => 'required',
            'editKota' => 'required',
            'editKecamatan' => 'required',
            'editKelurahan' => 'required',
            'editKodePos' => 'required',
        ]);
        $address->namaDepan = $request->editNamaDepan;
        $address->namaBelakang = $request->editNamaBelakang;
        $address->noHP = $request->editNoHp;
        $address->provinsi = $request->editProvinsi;
        $address->kota = $request->editKota;
        $address->kecamatan = $request->editKecamatan;
        $address->kelurahan = $request->editKelurahan;
        $address->kodePos = $request->editKodePos;
    
        $address->save();
        alert()->success('Success!', 'Berhasil menambahkan alamat');
        return redirect()->back();
    }

    public function deleteAddress(Request $request){
        $addressId = $request->input('addressId');
        $address = Alamat::find($addressId);

        if (!$address) {
            alert()->success('error!', 'Alamat tidak ditemukan');
            return redirect()->back();
        }
        try {
 
            $address->delete();

            alert()->success('Success!', 'Berhasil menghapus alamat');
            return redirect()->back();
        } catch (\Exception $e) {
            alert()->success('error!', 'Gagal menghapus alamat');
            return redirect()->back();
        }
    }

    public function checkoutFunc(Request $request){
        $userID = Auth::user()->id;
        $pembeli = Auth::user()->firstName." ". Auth::user()->lastName;
        $cartItems = session()->get('cart', []);
        $today = now();

        if (empty($cartItems)) {
            alert()->error('Error!', 'Cart anda kosong');
            return redirect()->back();
        }

        $totalPayment = 0;
        foreach ($cartItems as $item) {
            $totalPayment += $item['price'] * $item['quantity'];
        }
        $transactionAmount = $totalPayment;
        
        $this->validate($request, [
            'inputAddress' => 'required',
        ],[
            'inputAddress.required' => 'Mohon pilih alamat pengiriman.',
        ]);

        $address = Alamat::find($request->inputAddress);
        if (!$address) {
            alert()->error('Error!', 'Something went wrong. Please try again.');
            return back();
        }

        $addressSnap = $address->namaDepan . ' ' . $address->namaBelakang . ', ' . $address->noHP . ', ' . $address->detailAlamat.', '.$address->kodePos.', '.$address->provinsi.', '.$address->kota.', '.$address->kecamatan.', '.$address->kelurahan;
        
        DB::beginTransaction();
        try {          

            $latestTransaction = Htrans::orderBy('id', 'desc')->first(); 

            if ($latestTransaction) {  
                $lastKode = intval(substr($latestTransaction->kodeTrans, 2)); 
                $newKode = 'TR' . str_pad($lastKode + 1, 5, '0', STR_PAD_LEFT); 
            } else {
                $newKode = 'TR00001';
            }
            // Create htrans first
            $htrans = new Htrans();
            $htrans->kodeTrans = $newKode;
            $htrans->fkUserID = $userID;
            $htrans->namaPembeli = $pembeli;
            $htrans->addressSnapshot = $addressSnap;
            $htrans->tanggalPembelian = $today;
            $htrans->totalPembelian = $totalPayment;
            $htrans->discount = 0; 
            $htrans->save();

            $currentPoints = 0;
            $isMember = Membership::where('fkUserID', $userID)->first();
            if ($isMember) {
                $pointsEarned = floor($totalPayment / 500); 
                $pointRecord = Point::where('memberID', $isMember->memberID)
                ->where(function ($query) {
                    $query->where('tanggalKadaluwarsaPoin', '>', now())
                        ->orWhereNull('tanggalKadaluwarsaPoin');
                })
                ->get();
                foreach ($pointRecord as $point) {
                    $currentPoints += $point->jumlahPoin; 
                }
                if ($request->input('usePoin') && $currentPoints >= 1000) {
                    $pay = $totalPayment;
                    $totalPayment -= $currentPoints; 

                    if ($totalPayment < 0) {
                        $totalPayment = 0; 
                        $currentPoints = $pay;
                    }

                    // Deduct points from the user's account
                    Point::create([
                        'memberID' => $isMember->memberID,
                        'htransID' => $htrans->id, 
                        'tanggalPemberianPoin' => now(),
                        'jumlahPoin' => -$currentPoints,
                        'tipeTransaksi' => 'Redeem',
                        'sumberPoin' => 'Redeem Points for purchase',
                        'tanggalKadaluwarsaPoin' => null,
                        'saldoPoin' => 0,
                    ]);

                    $htrans->discount = $currentPoints;
                }
            }

            $htrans->totalPembelian = $totalPayment;
            $htrans->save(); 
            
            //menambah ke dtrans
            foreach ($cartItems as $item) {
                $dtrans = new Dtrans();
                $dtrans->fkHtransID = $htrans->id;
                $dtrans->fkProductID = $item['productID'];
                $dtrans->totalJumlah = $item['quantity'];
                $dtrans->satuanBarang = $item['unit'];
                $dtrans->hargaSatuan = $item['price'];
                $dtrans->subtotal = $item['quantity'] * $item['price'];
                $dtrans->save();
            }

            // kirim email
            $userEmail = Auth::user()->email; 
            $dtransItems = Dtrans::where('fkHtransID', $htrans->id)
            ->join('product', 'dtrans.fkProductID', '=', 'product.id')  
            ->select('dtrans.*', 'product.namaBarang', 'product.fotoPromosi')
            ->get();
                // $cartItems
            Mail::to($userEmail)->send(new ReceiptMail($htrans, $dtransItems));

            DB::commit();

            session()->forget('cart');
            alert()->success('Berhasil melakukan pemesanan!', 'harap melakukan pembayaran');
            return redirect('/');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: '.$e->getMessage(), [
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            alert()->error('Error!', 'Something went wrong. Please try again.');
            return back();
        }
    }
    
    public function uploadBuktiPembayaran(Request $request){
        $request->validate([
            'buktiPembayaran' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'transaction_id' => 'required|exists:htrans,id',
        ]);

        $transaction = Htrans::where('id', $request->transaction_id)
                            ->where('fkUserID', auth()->id())
                            ->firstOrFail();

        // Define the path where you want to store the image
        $webpDirectory = storage_path('app/public/bukti'); 
        $webpFileName = $transaction->kodeTrans . '.' . uniqid() . '.webp'; // KodeTrans.uniqid().webp
        $webpPath = 'bukti/' . $webpFileName;
        $outputWebPPath = storage_path('app/public/' . $webpPath);

        if (!File::exists($webpDirectory)) {
            File::makeDirectory($webpDirectory, 0755, true);
        }

        $this->convertToWebP($request->file('buktiPembayaran'), $outputWebPPath);
        $transaction->buktiPembayaran = $webpPath;
        $transaction->status=2;
        $transaction->save();
    return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload');
    }


    public function addRetur(Request $request){

         // Validate incoming request data
         $request->validate([
            'salesHeaderID' => 'required|integer',
            'userID' => 'required|integer',
            'fotoBarang' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alasanRetur' => 'required|string|max:500',
            'selectedItemsData' => 'required|string',
        ]);
        

        // Parse selected item data from the hidden input
        $selectedItem = json_decode($request->selectedItemsData, true);

        // $file = $request->file('fotoBarang');
        // $webpPath = 'uploads/' . uniqid() . '.webp'; // Define the path for the WebP image
        // $this->convertToWebP($file, public_path($webpPath));
    
        $thumbnail = $request->file('fotoBarang');
        $thumbnailName =uniqid() . '.webp';  
        $thumbnailPath = public_path('images/userUpload/' . $thumbnailName);
        $this->convertToWebP($thumbnail, $thumbnailPath);
        try{
            Retur::create([
                'fkHeaderID' => $request->salesHeaderID,
                'fkUserID' => $request->userID,
                'fkDtransID' => $selectedItem['id'],
                'fotoBarang' => $thumbnailName  , 
                'tanggalRetur' => now(),
                'alasanRetur' => $request->alasanRetur,
                'jumlahBarangRetur' => $selectedItem['quantity'],
                'satuanBarangRetur' => $selectedItem['unit'],
                'hargaPerBarang' => $selectedItem['price'],
                'subtotal' => $selectedItem['quantity'] * $selectedItem['price'],
                'status' => 1, 
            ]);
            return redirect()->back()->with('success', 'Return request submitted successfully!');
        } catch (\Exception $e) {
            alert()->error('Error!', 'Something went wrong. Please try again.');
            return back();
        }
    }

    public function cancelOrder(Request $request) {
        $id = $request->input('transactionID');
        $order = Htrans::find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }
    
        $membership = Membership::where('fkUserID', $order->fkUserID)->first();
        if ($membership) {
            Point::where('htransID', $id)->delete();
        }
    
        $order->status = 4; 
        $order->save();
    
        return redirect()->back()->with('success', 'Order has been canceled.');
    }
    
    public function toggleWishlist(Request $request){
        $user = Auth::user();
        $productId = $request->input('product_id');
    
        $wishlist = Wishlist::where('fkUserID', $user->id)
                            ->where('fkProductID', $productId)
                            ->first();
    
        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['in_wishlist' => false]);
        } else {
            Wishlist::create([
                'fkUserID' => $user->id,
                'fkProductID' => $productId
            ]);
            return response()->json(['in_wishlist' => true]);
        }
    }

    public function AddToMembership(Request $request){
        // Validate the input
        $request->validate([
            'tanggalLahir' => 'required|date',
        ]);

        // Get the current authenticated user
        $user = auth()->user(); // Ensure user is authenticated
        // dd($userID);
        // $user = $user = User::find($userID->id); 
        DB::beginTransaction();
        try {  
            if ($user instanceof User) {
                // Update the user's birthdate

                // dd('berhasil');
                $user->tanggalLahir = $request->input('tanggalLahir');
        
                // Save the updated user model
                if ($user->save()) {
                    // Check if the user is already a member
                    $membership = Membership::where('fkUserID', $user->id)->first();
        
                    // If not a member, add to the membership table
                    if (!$membership) {
                        Membership::create([
                            'fkUserID' => $user->id, // Ensure this is the correct column name
                            'tanggalDaftar' => now(), // Start date is the current date
                            'tanggalAkhir' => null, // Membership valid for 1 year
                            'statusMembership' => 1, // Active membership
                        ]);
                    }
                    DB::commit();
                    alert()->success('Berhasil Mendaftarkan Membership!', 'Membership anda sudah terdaftar');
                    return redirect()->back();
                }
            }

            return redirect()->back()->with('error', 'User not found.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: '.$e->getMessage(), [
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            alert()->error('Error!', 'Something went wrong. Please try again.');
            return back();
        }
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
                // If the file is already a WebP image, move it directly without conversion
                $file->move(dirname($outputWebPPath), basename($outputWebPPath));
                return;
            default:
                alert()->error('Error!', 'Unsupported image format');
                return back();
        }

        // Convert to WebP and save
        imagewebp($image, $outputWebPPath, 75); // 80 is the quality setting for WEBP (0-100)

        // Free memory
        imagedestroy($image);
    }

}
