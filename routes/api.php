<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register','register');
    Route::post('/login','auth');
    Route::get('/login','login')->name('login');
});

Route::controller(ApiController::class)->group(function () {
    Route::get('/categories','categories');
    Route::get('/products','products');
    Route::get('/products/{id}','oneProduct');
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/products','storeProduct');
        Route::patch('/products/{id}','updateProduct');
        Route::delete('/products/{id}','destroyProduct');
        
        Route::post('/products/{product_id}/assets','storeAsset');
        Route::delete('/products/{product_id}/assets/{asset_id}','destroyAsset');
    });
});
