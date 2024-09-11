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
    public function addAlamat(Request $request){
        $userID = Auth::user()->id;
        $this->validate($request,[
            'InputnamaDepan'=>'required',
            'InputnamaBelakang'=>'required',
            'InputDetail'=>'required',
            'Inputprovinsi'=>'required',
            'InputKodePos'=>'required',
            'InputnoHP'=>'required',
            'provinsi'=>'required',
        ]);
        dd($request->all());
        // try {
        //     $Adress = new Alamat();
        //     $Adress->fkUserID = $userID;
        //     $Adress->namaDepan = $request->InputnamaDepan;
        //     $Adress->namaBelakang = $request->InputnamaBelakang;
        //     $Adress->kodePos = $request->InputKodePos;
        //     $Adress->noHP = $request->InputnoHP;
        //     $Adress->provinsi = $request->provinsi;
        //     $Adress->kota = $request->kota ?? null;
        //     $Adress->kecamatan = $request->kecamatan ?? null;
        //     $Adress->kelurahan = $request->kelurahan ?? null;
        //     $Adress->detailAlamat = $request->InputDetail;
        //     $Adress->save();
        //     alert()->success('Success!','Berhasil menambahkan alamat');
        //     return back();
        // } catch (\Exception $e) {
        //     // return $e->getMessage();
        //     abort(500, 'Error while adding address');
        // }

    }
}
