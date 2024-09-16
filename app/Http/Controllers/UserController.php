<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Category;
use App\Models\Pictures;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    // view 
    public function checkoutPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();

        return view('customer.checkout', compact('address'));
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
            // Use mass assignment to create the address
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
        
    }
}
