<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Category;
use App\Models\Pictures;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('product.created_at', 'desc');
                    break;
                case 'price_high_to_low':
                    $query->orderBy('product.hargaKecil', 'desc');
                    break;
                case 'price_low_to_high':
                    $query->orderBy('product.hargaKecil', 'asc');
                    break;
                default:
                    $query->orderBy('product.created_at', 'asc'); // Default to newest
                    break;
            }
        } else {
            // Default sorting: newest
            $query->orderBy('product.created_at', 'asc');
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

    public function updatePassword(Request $request){
        $request->validate([
            'passwordLama' => 'required',
            'password' => 'required|string|min:8|confirmed', 
        ]);
        $userID = Auth::user()->id;
        $user = User::find($userID); 

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Optional: Verify the old password (remove this if you don't want to validate it)
        if ($request->has('passwordLama') && !Hash::check($request->passwordLama, $user->password)) {
            return back()->withErrors(['passwordLama' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Optionally, you can log the user out after password change
        Auth::logout();

        return redirect()->route('login')->with('success', 'Password successfully changed.');
    }
}
