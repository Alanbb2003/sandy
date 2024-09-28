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
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $pointRecord = Point::where('tanggalKaldaluarsaPoin', '>', now())->orWhereNull('tanggalKaldaluarsaPoin')->get();
            foreach ($pointRecord as $point) { 
                if ($point->jumlahPoin < 0) {
                    // If jumlahPoin is negative set to 0 
                    $currentPoints = 0;
                } else {
                    // Add points if they're non-negative
                    $currentPoints += $point->jumlahPoin;
                }
            }
        }
        $cart = session()->get('cart', []);
        $totalAmmount = 0;

        foreach ($cart as $item) {
            $totalAmmount += $item['price'] * $item['quantity'];
        }

        return view('customer.checkout', compact('address','totalAmmount','memberstatus','currentPoints'));
    }

    public function addressPage(){
        //get the user id
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();
        // echo $address;
        return view('customer.adressInput',compact('address'));
    }

    public function membershipPage(){

        return view('customer.membershipPage');
    }

    public function transactionPage(){
        $userId = Auth::user()->id; 
        // Fetch Htrans records for the authenticated user
        $htransRecords = Htrans::where('fkUserID', $userId)->get();

        return view('customer.transaction',compact('htransRecords'));
    }

    public function wishlistPage(){
        $userID = Auth::user()->id;
        $WishlistItems =  Wishlist::where('fkUserID', $userID)
        ->with('product') // Load related product data
        ->get();
        return view('customer.wishlist',compact('WishlistItems'));
    }

    // function
    public function addAlamat(Request $request){
        $userID = Auth::user()->id;
        // dd($request->all());

        // Validate the request
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
        //check validation
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
        // update the address
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
        // make a string to save snapshot of selected address
        $addressSnap = $address->namaDepan . ' ' . $address->namaBelakang . ', ' . $address->noHP . ', ' . $address->detailAlamat.', '.$address->kodePos.', '.$address->provinsi.', '.$address->kota.', '.$address->kecamatan.', '.$address->kelurahan;
        
        //  Start transaction for database integrity
        DB::beginTransaction();
        try {          

            // Create htrans first
            $htrans = new Htrans();
            $htrans->fkUserID = $userID;
            $htrans->fkAddressID = $request->inputAddress;
            $htrans->namaPembeli = $pembeli;
            $htrans->addressSnapshot = $addressSnap;
            $htrans->tanggalPembelian = $today;
            $htrans->totalPembelian = $totalPayment;
            $htrans->discount = 0; // Initialize discount
            $htrans->save(); // Save the htrans before point redemption

            $currentPoints = 0;
            $isMember = Membership::where('fkUserID', $userID)->first();
            if ($isMember) {
                // Calculate points
                $pointsEarned = floor($totalPayment / 500); // Example calculation for points
                $pointRecord = Point::where('tanggalKaldaluarsaPoin', '>', now())->orWhereNull('tanggalKaldaluarsaPoin')->get();
                foreach ($pointRecord as $point) {
                    $currentPoints += max(0, $point->jumlahPoin); // Ensure no negative points
                }

                // Check if redeem points checkbox is ticked
                if ($request->input('usePoin') && $currentPoints >= 1000) {
                    $totalPayment -= $currentPoints; // Apply all current points as a discount

                    if ($totalPayment < 0) {
                        $totalPayment = 0; // Ensure totalPayment doesn't go below zero
                    }

                    // Deduct points from the user's account
                    Point::create([
                        'memberID' => $isMember->memberID,
                        'htransID' => $htrans->id, // htransID now exists
                        'tanggalPemberianPoin' => now(),
                        'jumlahPoin' => -$currentPoints, // Use all current points
                        'tipeTransaksi' => 'Redeem',
                        'sumberPoin' => 'Redeem Points for purchase',
                        'tanggalKaldaluarsaPoin' => null,
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

                // Fetch the product from the database
                $product = Products::find($item['productID']);
                
                if ($product) {
                    if($item['unitHidden'] == 'small'){
                        $product->totalQuantity -= $item['quantity'];
                    }else{
                        $reducebig = $item['quantity'] * $product->isiSatuanBesar;
                        $product->totalQuantity -= $reducebig;
                    }
                    $product->save();
                }
            }

            if($isMember){
                $tanggalPemberian = Carbon::now(); // Today's date
                $tanggalKadaluwarsa = $tanggalPemberian->copy()->addYear(1); // Points expire after 1 year
                $tipeTransaksi = 'Purchase'; // Transaction type
                $sumberPoin = 'Pembelian dengan jumlah Rp.' .number_format($transactionAmount, 0, ',', '.');
                $saldoPoin = $pointsEarned; // Assuming current points equal earned points (adjust if needed)
    
                // Add points to the database
                Point::create([
                    'memberID' => $isMember->memberID,
                    'htransID'=>$htrans->id,
                    'tanggalPemberianPoin' => $tanggalPemberian,
                    'jumlahPoin' => $pointsEarned,
                    'tipeTransaksi' => $tipeTransaksi,
                    'sumberPoin' => $sumberPoin,
                    'tanggalKaldaluarsaPoin' => $tanggalKadaluwarsa,
                    'saldoPoin' => $saldoPoin,
                ]);
            }

            // Step 6: Send receipt via email
            $userEmail = Auth::user()->email; // Assuming the User model has an email field
            Mail::to($userEmail)->send(new ReceiptMail($htrans, $cartItems));

            // Commit the transaction
            DB::commit();

            // Clear cart after successful checkout
            session()->forget('cart');
            alert()->success('Berhasil melakukan pemesanan!', 'harap melakukan pembayaran');
            return redirect('/');

        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();
            Log::error('Checkout error: '.$e->getMessage(), [
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            alert()->error('Error!', 'Something went wrong. Please try again.');
            return back();
        }
    }

    public function toggleWishlist(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
    
        // Check if the product is already in the wishlist for the user
        $wishlist = Wishlist::where('fkUserID', $user->id)
                            ->where('fkProductID', $productId)
                            ->first();
    
        if ($wishlist) {
            // If the product is already in the wishlist, remove it
            $wishlist->delete();
            return response()->json(['in_wishlist' => false]);
        } else {
            // Add the product to the wishlist
            Wishlist::create([
                'fkUserID' => $user->id,
                'fkProductID' => $productId
            ]);
            return response()->json(['in_wishlist' => true]);
        }
    }
}
