<?php

use App\Http\Controllers\ProductTransactionController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    // return view('welcome');
    return redirect('login');
});

Route::group(['middleware' => ['checkrole:admin']], function () {


    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('profile-show', [UserController::class, 'showProfile'])->name('profile-show');;
        Route::patch('{user}/photo', [UserController::class, 'updatePhoto'])->name('photo-update');;
        Route::patch('{user}', [UserController::class, 'updateProfile'])->name('profile-update');
        Route::patch('{user}/password', [UserController::class, 'updatePassword'])->name('password-update');
        Route::patch('{user}/reset-password', [UserController::class, 'resetPassword'])->name('password-reset');
        Route::delete('{user}/destroy', [UserController::class, 'destroy'])->name('destroy');
        Route::get('users', [UserController::class, 'getUsers'])->name('users.index');
    });

    Route::resource('mechanics', MechanicController::class)->except(['create', 'edit', 'show']);
    Route::resource('products', ProductController::class)->except(['create', 'edit', 'show']);
    Route::resource('galleries', GalleryController::class)->except(['create', 'edit', 'show']);
    Route::resource('product-transactions', ProductTransactionController::class)->except(['create', 'edit', 'show']);
    // Route::get('mechanic/filter', [MechanicController::class, 'filter']);
    // Route::get('mechanic/{id}', [MechanicController::class, 'show']);


    Route::post('product-api', [GalleryController::class, 'getProducts'])->name('product-api');
    // Route::get('product', [ProductController::class, 'index']);
    // Route::get('product/filter', [ProductController::class, 'filter']);
    // Route::get('product/{id}', [ProductController::class, 'show']);

    // Route::get('gallery', [GalleryController::class, 'index']);
    // Route::get('gallery/filter', [GalleryController::class, 'filter']);
    // Route::get('gallery/{id}', [GalleryController::class, 'show']);

    // Route::get('cart-product', [CartProductController::class, 'index']);
    // Route::get('cart-product/filter', [CartProductController::class, 'filter']);
    // Route::get('cart-product/{user_id}', [CartProductController::class, 'show']);
    // Route::post('cart-product', [CartProductController::class, 'store']);
    // Route::delete('cart-product/{id}', [CartProductController::class, 'destroy']);
    // Route::patch('cart-product/mass-update/{user_id}', [CartProductController::class, 'massUpdate']);
    // Route::post('cart-product/mass-delete/{user_id}', [CartProductController::class, 'massDestroy']);

    // Route::get('product-transaction', [ProductTransactionController::class, 'getProdutTransaction']);
    // Route::get('mechanic-transaction', [MechanicTransactionController::class, 'getMechanicTransaction']);

    // for dev
    // Route::delete('product-transaction-co/{id}', [ProductTransactionController::class, 'destroy']);
    // Route::delete('mechanic-transaction-co/{id}', [MechanicTransactionController::class, 'destroy']);


});

// Guest
Route::group(['prefix' => 'v1',], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
