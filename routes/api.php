<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TagProductController;
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


Route::middleware('api')
    ->name('product')
    ->group(function (){
        Route::get('/products', [ProductController::class, 'index'])->name('index');
        Route::post('/products', [ProductController::class, 'store'])->name('create');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('show');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });


Route::get('/tags', [TagController::class, 'index'])->middleware('api')->name('tag.index');

Route::get('/tags/{tag}/products', TagProductController::class)->middleware('api')->name('tag.product.index');

