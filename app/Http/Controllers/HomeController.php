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
    // public function welcome(){
    //     $barang = Products::join('category','product.fk_kategori','=','category.id')
    //     ->select('product.*','category.nama_category as category')
    //     ->get();
    //     $category = Category ::all();
    //     return view('welcome',compact('barang','category'));
    // }
    public function welcome(Request $request) {
        // Start the query
        $query = Products::join('category', 'product.fk_kategori', '=', 'category.id')
            ->select('product.*', 'category.nama_category as category');
    
        // Filter by category
        if ($request->has('searchCategory') && $request->searchCategory != '') {
            $query->where('product.fk_kategori', $request->searchCategory);
        }
    
        // Filter by product name
        if ($request->has('searchName') && $request->searchName != '') {
            $query->where('product.namaBarang', 'LIKE', '%' . $request->searchName . '%');
        }
    
        // Filter by price range
        if ($request->has('minPrice') && $request->minPrice != '') {
            $query->where('product.hargaKecil', '>=', $request->minPrice);
        }
    
        if ($request->has('maxPrice') && $request->maxPrice != '') {
            $query->where('product.hargaKecil', '<=', $request->maxPrice);
        }
    
        // Get filtered products
        $barang = $query->get();
    
        // Get all categories
        $category = Category::all();
    
        return view('welcome', compact('barang', 'category'));
    }
    public function searchProducts(Request $request) {
        $query = Products::query();
    
        // Filter by category
        if ($request->has('searchCategory') && $request->searchCategory != '') {
            $query->where('category_id', $request->searchCategory);
        }
    
        // Filter by product name
        if ($request->has('searchName') && $request->searchName != '') {
            $query->where('name', 'LIKE', '%' . $request->searchName . '%');
        }
    
        // Filter by price range
        if ($request->has('minPrice') && $request->minPrice != '') {
            $query->where('price', '>=', $request->minPrice);
        }
    
        if ($request->has('maxPrice') && $request->maxPrice != '') {
            $query->where('price', '<=', $request->maxPrice);
        }
    
        // Get the filtered products
        $products = $query->get();
    
        // Pass the filtered products and categories back to the view
        return view('product.index', compact('products', 'category'));
    }
    public function showdetailBarang($slugBarang){
        $slug = $slugBarang;
        $product = Products::where('slugBarang', $slug)->firstOrFail();
        $barang = Products::join('category','product.fk_kategori','=','category.id')
        ->select('product.*','category.nama_category as category')
        ->where('product.slugBarang','=',$slug)
        ->firstOrFail();

        $pic=Pictures::where('productID','=',$product->id)->get();

        return view('customer.detailBarang',compact('barang','pic'));
    }
    // admin
    // public function adminHome(){
    //     return view('admin.homeAdmin',["msg"=>"I am admin role"]);
    // }
    // public function adminManageStock(){
    //     // $barang = Products::all();
    //     $barang = Products::join('category','product.id','=','category.id')
    //     ->select('product.*','category.nama_category as category')
    //     ->get();
    //     $kategori = Category::all();
    //     return view('admin.manageStock',compact('barang','kategori'));
    // }
    // public function adminBarangNew(){
    //     $kategori = Category::all();
    //     return view('admin.forms.addStock',compact('kategori'));
    // }
    // public function getCategories(){
    //     try {
    //         $categories = Category::all();
    //         return response()->json($categories);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to fetch categories. Please try again.'], 500);
    //     }
    // }
    // public function adminMembership(){

    //     return view('admin.manageMembership');
    // }


    //user/pelanggan
    

    public function returnBarang(){
        $category  = Category::all();


    }
    
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
    public function searchBarang(){
        
    }
}
