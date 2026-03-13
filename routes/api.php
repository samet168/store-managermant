<!-- 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockLogController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

//Route customers
Route::get('/customers', [CustomerController::class, 'index']);
Route::Post('/customers', [CustomerController::class, 'store']);
Route::post('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);



Route::get('/users', [UserController::class, 'index']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'edit']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

//Orders
Route::get('/orders', [OrderController::class, 'index']);       // List all orders
Route::post('/orders', [OrderController::class, 'store']);      // Create new order
Route::get('/orders/{id}', [OrderController::class, 'show']);   // Show single order
Route::put('/orders/{id}', [OrderController::class, 'update']); // Update order
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);


//


Route::get('/suppliers', [SupplierController::class,'index']);
Route::post('/suppliers', [SupplierController::class,'store']);
Route::get('/suppliers/{id}', [SupplierController::class,'show']);
Route::put('/suppliers/{id}', [SupplierController::class,'update']);
Route::delete('/suppliers/{id}', [SupplierController::class,'destroy']);

//


Route::get('/purchases', [PurchaseController::class,'index']);
Route::post('/purchases', [PurchaseController::class,'store']);
Route::get('/purchases/{id}', [PurchaseController::class,'show']);
Route::put('/purchases/{id}', [PurchaseController::class,'update']);
Route::delete('/purchases/{id}', [PurchaseController::class,'destroy']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'edit']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);





// Products
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'edit']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users/store', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'edit']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Categories
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{id}', [CategoryController::class, 'edit']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Products
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'edit']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Stock Logs
        Route::get('/stock-logs', [StockLogController::class, 'index']);
        Route::get('/stock-logs/{id}', [StockLogController::class, 'show']);
        Route::post('/stock-logs', [StockLogController::class, 'store']);
        Route::delete('/stock-logs/{id}', [StockLogController::class, 'destroy']);
    });


    /*
    |--------------------------------------------------------------------------
    | Staff Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('staff')->middleware('staff')->group(function () {

        // Categories
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{id}', [CategoryController::class, 'edit']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Products
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'edit']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Stock Logs
        Route::get('/stock-logs', [StockLogController::class, 'index']);
        Route::get('/stock-logs/{id}', [StockLogController::class, 'show']);
        Route::post('/stock-logs', [StockLogController::class, 'store']);
        Route::delete('/stock-logs/{id}', [StockLogController::class, 'destroy']);
    });

}); -->




<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockLogController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// View products and categories (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);



/*
|--------------------------------------------------------------------------
| Protected Routes (Need Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Users Management
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'edit']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Categories
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{id}', [CategoryController::class, 'edit']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Products
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'edit']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Suppliers
        Route::get('/suppliers', [SupplierController::class,'index']);
        Route::post('/suppliers', [SupplierController::class,'store']);
        Route::get('/suppliers/{id}', [SupplierController::class,'show']);
        Route::put('/suppliers/{id}', [SupplierController::class,'update']);
        Route::delete('/suppliers/{id}', [SupplierController::class,'destroy']);

        // Purchases
        Route::get('/purchases', [PurchaseController::class,'index']);
        Route::post('/purchases', [PurchaseController::class,'store']);
        Route::get('/purchases/{id}', [PurchaseController::class,'show']);
        Route::put('/purchases/{id}', [PurchaseController::class,'update']);
        Route::delete('/purchases/{id}', [PurchaseController::class,'destroy']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::put('/orders/{id}', [OrderController::class, 'update']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

        // Stock Logs
        Route::get('/stock-logs', [StockLogController::class, 'index']);
        Route::get('/stock-logs/{id}', [StockLogController::class, 'show']);
        Route::post('/stock-logs', [StockLogController::class, 'store']);
        Route::delete('/stock-logs/{id}', [StockLogController::class, 'destroy']);
    });



    /*
    |--------------------------------------------------------------------------
    | Manager Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:manager')->prefix('manager')->group(function () {

        // View products
        Route::get('/products', [ProductController::class, 'index']);
        Route::put('/products/{id}', [ProductController::class, 'update']);

        // Stock management
        Route::get('/stock-logs', [StockLogController::class, 'index']);
        Route::post('/stock-logs', [StockLogController::class, 'store']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });



    /*
    |--------------------------------------------------------------------------
    | Cashier Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cashier')->prefix('cashier')->group(function () {

        // View products
        Route::get('/products', [ProductController::class, 'index']);

        // Create orders / invoices
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);

        // Customers
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers', [CustomerController::class, 'store']);
    });



    /*
    |--------------------------------------------------------------------------
    | Supplier Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:supplier')->prefix('supplier')->group(function () {

        // View purchase orders
        Route::get('/purchases', [PurchaseController::class,'index']);
        Route::get('/purchases/{id}', [PurchaseController::class,'show']);

        // Update delivery status
        Route::put('/purchases/{id}', [PurchaseController::class,'update']);
    });



    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:customer')->prefix('customer')->group(function () {

        // View products
        Route::get('/products', [ProductController::class, 'index']);

        // Create orders
        Route::post('/orders', [OrderController::class, 'store']);

        // View own orders
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });

});