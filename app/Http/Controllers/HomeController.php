<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Category;
use App\Models\Pictures;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function welcome(){
        $barang = Products::join('category','product.id','=','category.id')
        ->select('product.*','category.nama_category as category')
        ->get();
        $category = Category ::all();
        return view('welcome',compact('barang','category'));
    }

    // admin
    public function adminHome(){
        return view('admin.homeAdmin',["msg"=>"I am admin role"]);
    }
    public function adminManageStock(){
        // $barang = Products::all();
        $barang = Products::join('category','product.id','=','category.id')
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


    //user/pelanggan
    public function userHome(){
        $barang = Products::join('category','product.id','=','category.id')
        ->select('product.*','category.nama_category as category')
        ->get();
        return view('customer.homeCustomer',["msg"=>"I am user role"]);
    }
    public function returnBarang(){
        $category  = Category::all();


    }

    // public function detailBarang(Request $request, $productName){
    //     $productId = $request->input('productId');

    //     $barang = Products::join('category','product.id','=','category.id')
    //     ->select('product.*','category.nama_category as category')
    //     ->where('product.id','=',$productId)
    //     ->first();

    //     $pic=Pictures::where('productID','=',$productId)->get();

    //     return view('customer.detailBarang',compact('barang','pic'));
    // }
    public function showdetailBarang($slugBarang){
        $slug = $slugBarang;
        $product = Products::where('slugBarang', $slug)->firstOrFail();
        $barang = Products::join('category','product.id','=','category.id')
        ->select('product.*','category.nama_category as category')
        ->where('product.slugBarang','=',$slug)
        ->firstOrFail();

        $pic=Pictures::where('productID','=',$product->id)->get();

        return view('customer.detailBarang',compact('barang','pic'));
    }
    public function checkoutPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();

        
        return view('customer.checkout', compact('address'));
    }
    public function addressPage(){
        $userID = Auth::user()->id;
        $address = Alamat::where('fkUserID',$userID)->get();
        echo $address;
        return view('customer.adressInput',compact('address'));
    }
    public function searchBarang(){
        
    }
}
