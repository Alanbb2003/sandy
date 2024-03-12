<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//user route
Route::middleware(['auth','user-role:user'])->group(function(){
    Route::get("/home",[HomeController::class,'userHome'])->name('home');
});

//admin route
Route::middleware(['auth','user-role:admin'])->group(function(){
    Route::get("/Dashboard",[HomeController::class,'adminHome'])->name('homeAdmin');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
