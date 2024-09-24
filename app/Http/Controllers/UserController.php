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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    // view 
    public function checkoutPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();

        $memberstatus = Membership::where('fkUserID','=',$userID)->get();
        $cart = session()->get('cart', []);
        $totalAmmount = 0;

        foreach ($cart as $item) {
            $totalAmmount += $item['price'] * $item['quantity'];
        }

        return view('customer.checkout', compact('address','totalAmmount','memberstatus'));
    }

    public function addressPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();
        // echo $address;
        return view('customer.adressInput',compact('address'));
    }

    public function membershipPage(){

        return view('customer.membershipPage');
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

    public function checkoutFunc(Request $request){
        $userID = Auth::user()->id;
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            alert()->error('Error!', 'Cart anda kosong');
            return redirect()->back();
        }

        $totalPayment = 0;
        foreach ($cartItems as $item) {
            $totalPayment += $item['price'] * $item['quantity'];
        }

        $this->validate($request, [
            'inputAddress' => 'required',
        ]);

        $address = Alamat::find($request->inputAddress);
        if (!$address) {
            alert()->error('Error!', 'invalid address');
            return redirect()->back();
        }
        
        $addressSnap = $address->namaDepan . ' ' . $address->namaBelakang . ', ' . $address->noHP . ', ' . $address->detailAlamat.', '.$address->kodePos.', '.$address->provinsi.', '.$address->kota.', '.$address->kecamatan.', '.$address->kelurahan;
         echo $addressSnap;
        dd($addressSnap);
        
        //  Start transaction for database integrity
        DB::beginTransaction();
        try {
            // Step 1: Save Htrans (Header Transaction)
            $htrans = new Htrans();
            $htrans->fkUserID = $userID;
            $htrans->totalPayment = $totalPayment;
            $htrans->address = $request->inputAddress;
            $htrans->save(); // Save to get Htrans ID
            
            // Step 2: Save Dtrans (Detail Transactions for each item)
            foreach ($cartItems as $item) {
                $dtrans = new Dtrans();
                $dtrans->fkHtransID = $htrans->id;
                $dtrans->fkBarangID = $item['id'];
                $dtrans->quantity = $item['quantity'];
                $dtrans->price = $item['price'];
                $dtrans->save();
            }

            // Step 3: Check if user is in membership table
            $isMember = Membership::where('fkUserID', $userID)->exists();

            if ($isMember) {
                // Step 4: Calculate points
                $pointsEarned = floor($totalPayment / 500);

                // Step 5: Save points to the points table
                $point = new Point();
                $point->fkUserID = $userID;
                $point->points = $pointsEarned;
                $point->description = "Points earned from checkout on Htrans ID: $htrans->id";
                $point->save();
            }

            // Step 6: Send receipt via email
            $userEmail = Auth::user()->email; // Assuming the User model has an email field
            Mail::to($userEmail)->send(new ReceiptMail($htrans, $cartItems));

            // Commit the transaction
            DB::commit();

            // Clear cart after successful checkout
            session()->forget('cart');

            return back()->with('success', 'Checkout completed successfully! Receipt has been sent to your email.');

        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();
            return back()->with('error', 'There was an issue during checkout. Please try again.');
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
