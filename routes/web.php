<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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


Route::get('/', [GuestController::class, 'index'])->name('index');

Route::get('/set-locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return back();
})->name('set-locale');

Route::prefix('system')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('user', UserController::class)->except('create', 'show', 'edit');
    Route::post('user/destroy-bulk', [UserController::class, 'destroyBulk'])->name('user.destroy-bulk');

    Route::resource('role', RoleController::class)->except('create', 'show', 'edit');
    Route::post('role/destroy-bulk', [RoleController::class, 'destroyBulk'])->name('role.destroy-bulk');

    Route::resource('permission', PermissionController::class)->except('create', 'show', 'edit');
    Route::post('permission/destroy-bulk', [PermissionController::class, 'destroyBulk'])->name('permission.destroy-bulk');

    Route::resource('activity', ActivityController::class)->except('create', 'show', 'edit', 'store', 'update');
    Route::post('activity/destroy-bulk', [ActivityController::class, 'destroyBulk'])->name('activity.destroy-bulk');

    Route::resource('setting', SettingController::class)->except('create', 'store', 'show', 'edit', 'destory');

    Route::resource('product', ProductController::class)->except('create', 'show', 'edit');
    Route::post('product/destroy-bulk', [ProductController::class, 'destroyBulk'])->name('product.destroy-bulk');
    Route::post('product/import', [ProductController::class, 'import'])->name('product.import');
    Route::get('product/template', [ProductController::class, 'template'])->name('product.template');


    Route::resource('category', CategoryController::class)->except('create', 'show', 'edit');
    Route::post('category/destroy-bulk', [CategoryController::class, 'destroyBulk'])->name('category.destroy-bulk');

    Route::resource('order', OrderController::class)->except('create', 'show', 'edit');
    Route::post('order/addToCart', [OrderController::class, 'addToCart'])->name('order.addToCart');
    Route::post('editQuantity/{id}', [OrderController::class, 'editQuantity'])->name('order.edit-quantity');
    Route::post('order/clearCart', [OrderController::class, 'clearCart'])->name('order.clear-cart');
    Route::get('order/print/{id}', [OrderController::class, 'print'])->name('order.print');

    Route::resource('customer', CustomerController::class)->except('create', 'show', 'edit');
    Route::post('customer/destroy-bulk', [CustomerController::class, 'destroyBulk'])->name('customer.destroy-bulk');


    Route::resource('income', IncomeController::class)->only('index', 'destroy');
    Route::post('income/destroy-bulk', [IncomeController::class, 'destroyBulk'])->name('income.destroy-bulk');
    Route::get('income/export', [IncomeController::class, 'export'])->name('income.export');



    require __DIR__ . '/jarvis.php';
});
