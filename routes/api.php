<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\CartController;
use \App\Http\Middleware\IsSeller;
use \App\Http\Middleware\IsCustomer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [UserController::class,'login'])->name('login');
Route::post('/register', [UserController::class,'register'])->name('register');


Route::group([ "middleware" => "auth:api" ],function() {

    Route::group([ "middleware" =>  "IsSeller"] ,function() {
        Route::post('/all-products', [ProductController::class,'index'])->name('all_products');
        Route::post('/show-product', [ProductController::class,'show_product'])->name('show_product');
        Route::post('/add-product', [ProductController::class,'store'])->name('add_product');
        Route::post('/update-product', [ProductController::class,'update'])->name('update_product');
        Route::post('/delete-product', [ProductController::class,'destroy'])->name('delete_product');


    });

    Route::group([ "middleware" =>  "IsCustomer"] ,function() {

        Route::post('/checkout', [CartController::class,'checkout'])->name('checkout');
        Route::post('/add-to-cart', [CartController::class,'addToCart'])->name('add_to_cart');
        Route::post('/delete-product-from-cart', [CartController::class,'deleteFromCart'])->name('delete_from_cart');
        Route::post('/edit-product-in-cart', [CartController::class,'editProductInCart'])->name('edit_product_in_cart');


    });


//    Route::apiResource('products', ProductController::class);


});

