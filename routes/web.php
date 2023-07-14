<?php

use App\Http\Controllers\Dashboard\AccountController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DesignationController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\ExpenseCategoryController;
use App\Http\Controllers\Dashboard\ExpenseController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\OutletController;
use App\Http\Controllers\Dashboard\ProductCategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\PurchaseController;
use App\Http\Controllers\Dashboard\SaleController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\UnitController;
use App\Http\Controllers\Dashboard\WarehouseController;
use App\Http\Controllers\HomeController;
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

//Route::get('/', function () {
//    return view('auth.login');
//});

Auth::routes();



Route::group(['middleware' => 'auth'], function(){

    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

//    resource route
    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::resource('products-categories', ProductCategoryController::class)->only(['index', 'store']);
    Route::resource('supplier', SupplierController::class)->except(['destroy']);;
    Route::resource('customer', CustomerController::class)->except(['destroy']);;
    Route::resource('employee', EmployeeController::class)->except(['destroy']);;
    Route::resource('purchase', PurchaseController::class)->except(['destroy']);;
    Route::resource('sale', SaleController::class);
    Route::resource('order', OrderController::class)->except(['delete']);;
    Route::resource('outlet', OutletController::class)->except(['delete']);
    Route::resource('designation', DesignationController::class)->only(['index', 'store']);
    Route::resource('unit', UnitController::class)->only(['index', 'store']);
    Route::resource('warehouse', WarehouseController::class)->except(['delete']);
    Route::resource('expense', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);

    Route::group(['prefix' => 'products-categories','as'=>'products-categories.'], function(){
        Route::get('delete/{id}', [ProductCategoryController::class,'destroy'])->name('delete');
        Route::post('update', [ProductCategoryController::class,'update'])->name('update');
    });

    Route::group(['prefix' => 'products','as'=>'products.'], function(){
        Route::get('delete/{id}', [ProductController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'supplier','as'=>'supplier.'], function(){
        Route::get('delete/{id}', [SupplierController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'customer','as'=>'customer.'], function(){
        Route::get('delete/{id}', [CustomerController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'purchase','as'=>'purchase.'], function(){
        Route::get('delete/{id}', [PurchaseController::class,'destroy'])->name('delete');
        Route::post('purchase-payment-render', [PurchaseController::class,'purchase_payment_render'])->name('payment.render');
        Route::post('purchase-payment', [PurchaseController::class,'purchase_payment'])->name('payment');
    });

    Route::group(['prefix' => 'order','as'=>'order.'], function(){
        Route::get('delete/{id}', [OrderController::class,'destroy'])->name('delete');
        Route::post('order-payment-render', [OrderController::class,'order_payment_render'])->name('payment.render');
        Route::post('order-payment', [OrderController::class,'order_payment'])->name('payment');
    });

    Route::group(['prefix' => 'employee','as'=>'employee.'], function(){
        Route::get('delete/{id}', [EmployeeController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'expense-categories','as'=>'expense-categories.'], function(){
        Route::get('delete/{id}', [ExpenseCategoryController::class,'destroy'])->name('delete');
        Route::post('update', [ExpenseCategoryController::class,'update'])->name('update');
    });

    Route::group(['prefix' => 'expense-categories','as'=>'expense-categories.'], function(){
        Route::get('delete/{id}', [ExpenseCategoryController::class,'destroy'])->name('delete');
        Route::post('update', [ExpenseCategoryController::class,'update'])->name('update');
    });

    Route::group(['prefix' => 'warehouse','as'=>'warehouse.'], function(){
        Route::get('delete/{id}', [WarehouseController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'outlet','as'=>'outlet.'], function(){
        Route::get('delete/{id}', [OutletController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'designation','as'=>'designation.'], function(){
        Route::post('update', [DesignationController::class,'update'])->name('update');
        Route::get('delete/{id}', [DesignationController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'unit','as'=>'unit.'], function(){
        Route::post('update', [UnitController::class,'update'])->name('update');
        Route::get('delete/{id}', [UnitController::class,'destroy'])->name('delete');
    });
});


