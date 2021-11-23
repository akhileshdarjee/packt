<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;

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

Route::get('/', [WebsiteController::class, 'index'])->name('index');
Route::get('/products', [WebsiteController::class, 'products'])->name('products');
Route::get('/product/{id}', [WebsiteController::class, 'singleProduct'])->name('single.product');
Route::get('/product/{id}/image/{size?}', [WebsiteController::class, 'productImage'])->name('product.image');
