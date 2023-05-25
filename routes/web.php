<?php

use App\Http\Controllers\MechanicController;
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
    return view('welcome');
});

Route::group(['middleware' => ['checkrole:admin']], function () {

    Route::get('user', [UserController::class, 'showProfile']);
    Route::patch('user/{id}/photo', [UserController::class, 'updatePhoto']);
    Route::patch('user/{id}', [UserController::class, 'updateProfile']);

    Route::resource('mechanics', MechanicController::class)->except(['create', 'edit']);
    // Route::get('mechanic/filter', [MechanicController::class, 'filter']);
    // Route::get('mechanic/{id}', [MechanicController::class, 'show']);

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
