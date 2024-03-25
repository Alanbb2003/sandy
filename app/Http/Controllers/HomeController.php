<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function userHome(){
        return view('home',["msg"=>"I am user role"]);
    }

    // admin
    public function adminHome(){
        return view('admin.homeAdmin',["msg"=>"I am admin role"]);
    }
    public function adminManageStock(){
        $barang = Products::all();
        $barang = Products::join('category','product.id','=','category.id')
        ->select('product.*','category.nama as category')
        ->get();
        $kategori = Category::all();
        return view('admin.manageStock',compact('barang','kategori'));
    }
}
