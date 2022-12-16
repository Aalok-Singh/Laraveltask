<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/products-list', [App\Http\Controllers\ProductController::class, 'list'])->name('products-list');
Route::get('/addproduct', [App\Http\Controllers\ProductController::class, 'create'])->name('addproduct');
Route::post('/addproduct', [App\Http\Controllers\ProductController::class, 'store'])->name('addproduct');
Route::get('/editproduct/{product_id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('editproduct');
Route::post('/deleteproduct', [App\Http\Controllers\ProductController::class, 'destroy'])->name('deleteproduct');
Route::post('/deleteimage', [App\Http\Controllers\ProductController::class, 'deleteimage'])->name('deleteimage');
Route::post('/editproduct', [App\Http\Controllers\ProductController::class, 'editproduct'])->name('editproduct');