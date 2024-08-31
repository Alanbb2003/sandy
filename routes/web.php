<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {return view('welcome');});

Route::get('/', [HomeController::class,"welcome"]);
Route::get("/product/{slug}",[HomeController::class,'showdetailBarang']);
// Route::get("/product/{productName}",[HomeController::class,'detailBarang']);

Auth::routes([
    'verify'=>true
]);

Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/addOne/{id}', [CartController::class, 'addOne']);
Route::get('/cart/removeOne/{id}', [CartController::class, 'removeOne']);
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

//user route
Route::middleware(['auth','user-role:user'])->group(function(){
    Route::prefix('/home',)->group(function(){
        // Route::get("/",[HomeController::class,'userHome'])->name('home')->middleware('verified');
        Route::get('/', [HomeController::class,"welcome"])->name('home')->middleware('verified');
        // Route::get("/product/{productName}",[HomeController::class,'detailBarang']);
        
        // Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        // Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
        // Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
        // Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    });
});

//admin route
Route::middleware(['auth','user-role:admin'])->group(function(){
    Route::prefix('/dashboard',)->group(function(){
        Route::get("/",[HomeController::class,'adminHome'])->name('homeAdmin');
        Route::get('/barang',[HomeController::class,'adminManageStock']);
        Route::get('/barang/new',[HomeController::class,'adminBarangNew']);
        Route::get('/barang/new/get-categories',[HomeController::class,'getCategories']);
        Route::post('/barang/new',[AdminController::class,'addBarang']);
        Route::post('/barang/new/kategori',[AdminController::class,"addKategori"]);
        Route::post('/barang/new/kategori/update-category', [AdminController::class, 'updateKategori']);
        // Route::post('/barang/kategori',[AdminController::class,"addKategori"]);
    });
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
