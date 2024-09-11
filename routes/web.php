<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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

Auth::routes([
    'verify'=>true
]);

Route::prefix('/cart')->group(function() {
    Route::get('/', [CartController::class, 'view'])->name('cart.view');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/addOne/{id}', [CartController::class, 'addOne']);
    Route::get('/removeOne/{id}', [CartController::class, 'removeOne']);
    Route::get('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::get('/checkout',[HomeController::class,"checkoutPage"])->middleware('verified');
Route::get('/address',[HomeController::class,"addressPage"]);
Route::post('/address/add',[UserController::class,"addAlamat"])->middleware('verified');
//user route
Route::middleware(['auth','user-role:user'])->group(function(){
    Route::prefix('/home',)->group(function(){
        // Route::get("/",[HomeController::class,'userHome'])->name('home')->middleware('verified');
        Route::get('/', [HomeController::class,"welcome"])->name('home')->middleware('verified');
        Route::get('/checkout',[HomeController::class,"checkoutPage"])->middleware('verified');
        Route::get('/address',[HomeController::class,"addressPage"]);
        Route::post('/address/add',[UserController::class,"addAlamat"])->middleware('verified');
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

///aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa

// General Routes
// Route::get('/', [HomeController::class,"welcome"])->name('home');
// Route::get("/product/{slug}", [HomeController::class, 'showdetailBarang'])->name('product.details');

// Auth::routes(['verify' => true]);

// Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
// Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
// Route::post('/cart/addOne/{id}', [CartController::class, 'addOne']); // changed to POST
// Route::post('/cart/removeOne/{id}', [CartController::class, 'removeOne']); // changed to POST
// Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Route::get('/checkout', [HomeController::class, "checkoutPage"])->middleware('verified');
// Route::get('/address', [HomeController::class, "addressPage"])->name('address.page');
// Route::post('/address/add', [UserController::class, "addAddress"])->middleware('verified');

// // User Routes
// Route::middleware(['auth', 'user-role:user'])->prefix('/home')->group(function () {
//     Route::get('/', [HomeController::class, "welcome"])->name('user.home')->middleware('verified');
//     Route::get('/checkout', [HomeController::class, "checkoutPage"])->middleware('verified');
//     Route::get('/address', [HomeController::class, "addressPage"]);
//     Route::post('/address/add', [UserController::class, "addAddress"])->middleware('verified');
// });

// // Admin Routes
// Route::middleware(['auth', 'user-role:admin'])->prefix('/dashboard')->group(function () {
//     Route::get('/', [HomeController::class, 'adminHome'])->name('admin.home');
//     Route::get('/barang', [HomeController::class, 'adminManageStock'])->name('admin.manageStock');
//     Route::get('/barang/new', [HomeController::class, 'adminBarangNew'])->name('admin.newBarang');
//     Route::get('/barang/new/get-categories', [HomeController::class, 'getCategories'])->name('admin.getCategories');
//     Route::post('/barang/new', [AdminController::class, 'addBarang'])->name('admin.addBarang');
//     Route::post('/barang/new/kategori', [AdminController::class, "addKategori"])->name('admin.addKategori');
//     Route::post('/barang/new/kategori/update-category', [AdminController::class, 'updateKategori'])->name('admin.updateKategori');
// });
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
