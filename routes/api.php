<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;




//----------------------------
//---- public Routes -----
//----------------------------
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
//----------------------------
//---- Auth Routes -----
//----------------------------
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});




Route::middleware('auth:sanctum')->group(function () {
    Route::controller(OrderController::class)->group(function () {
        Route::post('/order/add', 'store');
        Route::get('/order/{id}', 'show');
        Route::delete('/order/{id}', 'destroy');
    });
    Route::get('/user', [UsersController::class, 'current']);

    Route::post('/logout', [AuthController::class, 'logout']);
});



//----------------------------
//---- protected Routes -----
//----------------------------

Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {

    //----------------------------
    //---- Users Routes -----
    //----------------------------
    // store function it is register function so we don't need to create one
    Route::controller(UsersController::class)->group(function () {
        Route::get('/users', 'index');
        Route::post('/user/{id}', 'update');
        Route::get('/user/{id}', 'show');
        Route::delete('/user/{id}', 'destroy');
    });

    //----------------------------
    //---- Categories Routes -----
    //----------------------------
    Route::controller(CategoryController::class)->group(function () {
        Route::post('/category/add', 'store');
        Route::get('/category/{id}', 'show');
        Route::post('/category/{id}', 'update');
        Route::delete('/category/{id}', 'destroy');
    });



    //----------------------------
    //---- Products Routes -----
    //----------------------------

    Route::controller(ProductController::class)->group(function () {
        Route::post('/product/add',  'store');
        Route::post('/product/{id}',  'update');
        Route::delete('/product/{id}',  'destroy');
        Route::get('/products/{vendor_id}',  'ShowAllProductsReturnToVendor');
    });

    //----------------------------
    //---- Vendors Routes -----
    //----------------------------
    Route::controller(VendorController::class)->group(function () {
        Route::get('/vendors', 'index');
        Route::post('/vendor/add', 'store');
        Route::post('/vendor/{id}', 'update');
        Route::get('/vendor/{id}', 'show');
        Route::delete('/vendor/{id}', 'destroy');
    });

    //----------------------------
    //---- orders Routes -----
    //----------------------------
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::post('/order/{id}', 'update');
    });
});
