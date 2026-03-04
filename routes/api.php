<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/',[userController::class, 'index'])->name('user.index');
Route::post('/store', [userController::class, 'store'])->name('user.store');
Route::get('/edit/{id}', [userController::class, 'edit'])->name('user.edit');
Route::post('/update/{id}', [userController::class, 'update'])->name('user.update');
Route::Delete('/delete/{id}', [userController::class, 'destroy'])->name('user.destroy');


//Route Category
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::Post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::Delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');


//Route Product
Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::Post('/product/store', [ProductController::class, 'store'])->name('product.store');
Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
Route::Delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

//Route Stock 
Route::get('/stock', [ProductController::class, 'index'])->name('stock.index');
Route::post('/stock/store', [ProductController::class, 'store'])->name('stock.store');
Route::get('/stock/edit/{id}', [ProductController::class, 'edit'])->name('stock.edit');
Route::post('/stock/update/{id}', [ProductController::class, 'update'])->name('stock.update');
Route::Delete('/stock/delete/{id}', [ProductController::class, 'destroy'])->name('stock.destroy');
