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

Route::get('/', [HomeController::class,"welcome"])->name('home.main');
Route::get("/product/{slug}",[HomeController::class,'showdetailBarang']);
// Route::get('/product/search', [HomeController::class, 'searchProducts'])->name('product.search');
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

// Route::middleware(['auth','user-role:user'])->group(function(){
//     Route::get('/checkout',[UserController::class,"checkoutPage"])->middleware('verified');
//     Route::post('/checkout',[UserController::class,"checkoutFunc"])->middleware('verified');
//     Route::get('/address',[UserController::class,"addressPage"])->middleware('verified');
//     Route::post('/address/add',[UserController::class,"addAlamat"])->middleware('verified');
//     Route::put('/address/edit', [UserController::class, 'updateAddress'])->middleware('verified');
//     Route::get('/membership',[UserController::class,'membershipPage'])->middleware('verified');
//     Route::get('/wishlist', [UserController::class, 'wishlistPage'])->middleware('verified');
//     Route::post('/wishlist/toggle', [UserController::class, 'toggleWishlist'])->middleware('verified');
//     Route::get('/transaction',[UserController::class,'transactionPage'])->middleware('verified');
// });

Route::middleware(['auth', 'user-role:user', 'verified'])->group(function() {
    Route::get('/checkout', [UserController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [UserController::class, 'checkoutFunc'])->name('checkout.func');
    
    Route::get('/address', [UserController::class, 'addressPage'])->name('address.page');
    Route::post('/address/add', [UserController::class, 'addAlamat'])->name('address.add');
    Route::put('/address/edit', [UserController::class, 'updateAddress'])->name('address.edit');
    Route::delete('/address/delete', [UserController::class, 'deleteAddress'])->name('address.delete');

    Route::get('/membership', [UserController::class, 'membershipPage'])->name('membership.page');
    
    Route::get('/wishlist', [UserController::class, 'wishlistPage'])->name('wishlist.page');
    Route::post('/wishlist/toggle', [UserController::class, 'toggleWishlist'])->name('wishlist.toggle');
    
    Route::get('/transaction', [UserController::class, 'transactionPage'])->name('transaction.page');

    Route::get('/retur', [UserController::class, 'showReturnHistory'])->name('retur.page');
    Route::post('/retur/add', [UserController::class, 'addRetur'])->name('retur.store');
    Route::get('/get-transaction-items/{id}', [UserController::class, 'getTransactionItems']);

    Route::get('/profile',[UserController::class,'profilePage']);
    Route::put('/profile/password/update', [HomeController::class, 'updatePassword'])->name('password.update');
});
//user route


    // Route::prefix('/home',)->group(function(){
        // Route::get('/', [HomeController::class,"welcome"])->name('home')->middleware('verified');
        // Route::get('/checkout',[HomeController::class,"checkoutPage"])->middleware('verified');
        // Route::get('/address',[HomeController::class,"addressPage"]);
        // Route::post('/address/add',[UserController::class,"addAlamat"])->middleware('verified');
    // });


//admin route
Route::middleware(['auth','user-role:admin'])->group(function(){
    Route::prefix('/dashboard',)->group(function(){
        Route::get("/",[AdminController::class,'adminHome'])->name('homeAdmin');
        Route::get('/barang',[AdminController::class,'adminManageStock']);
        //add new barang
        Route::get('/barang/new',[AdminController::class,'adminBarangNew']);
        Route::get('/barang/new/get-categories',[AdminController::class,'getCategories']);
        Route::post('/barang/new',[AdminController::class,'addBarang']);    
        Route::post('/barang/new/kategori',[AdminController::class,"addKategori"]);
        Route::post('/barang/new/kategori/update-category', [AdminController::class, 'updateKategori']);
        //edit barang
        Route::get('/barang/edit/{id}', [AdminController::class, 'showeditBarang'])->name('dashboard.barang.edit');
        Route::put('/barang/edit/{id}', [AdminController::class, 'updateBarang']);
        Route::delete('/barang/delete-image/{id}', [AdminController::class, 'deleteImage'])->name('dashboard.barang.deleteImage');
        // Route::post('/barang/kategori',[AdminController::class,"addKategori"]);

        Route::get('/transaksi',[AdminController::class,"adminTransaksi"])->name('admin.transaksi');
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
