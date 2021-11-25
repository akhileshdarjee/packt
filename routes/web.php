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
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');
Route::get('/products', [WebsiteController::class, 'products'])->name('products');
Route::get('/product/{id}', [WebsiteController::class, 'singleProduct'])->name('single.product');
Route::get('/product/{id}/image/{size?}', [WebsiteController::class, 'getProductImage'])->name('product.image');
Route::get('/latest-products', [WebsiteController::class, 'latestProducts'])->name('latest.products');
Route::post('/set-default-currency', [WebsiteController::class, 'setDefaultCurrency'])->name('default.currency');
