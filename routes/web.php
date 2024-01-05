<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index',[
        'categories' => Category::withCount('products')->orderByDesc('products_count')->get(),
        'products' => Product::with('assets')->orderBy('price', 'desc')->get()
    ]);
});

Route::fallback(function () {
    return redirect('/');
});