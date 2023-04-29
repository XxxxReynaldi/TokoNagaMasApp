<?php


use App\Http\Controllers\API\CartProductController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\MechanicController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['checkroleapi:admin,customer']], function () {
    Route::group(['prefix' => 'v1', 'as' => 'v1.',], function () {

        Route::get('user', [UserController::class, 'showProfile']);
        Route::patch('user/{id}/photo', [UserController::class, 'updatePhoto']);
        Route::patch('user/{id}', [UserController::class, 'updateProfile']);
        Route::post('logout', [UserController::class, 'logout']);

        Route::get('mechanic', [MechanicController::class, 'index']);
        Route::get('mechanic/filter', [MechanicController::class, 'filter']);
        Route::get('mechanic/{id}', [MechanicController::class, 'show']);

        Route::get('product', [ProductController::class, 'index']);
        Route::get('product/filter', [ProductController::class, 'filter']);
        Route::get('product/{id}', [ProductController::class, 'show']);

        Route::get('gallery', [GalleryController::class, 'index']);
        Route::get('gallery/filter', [GalleryController::class, 'filter']);
        Route::get('gallery/{id}', [GalleryController::class, 'show']);

        Route::get('cart-product', [CartProductController::class, 'index']);
        Route::get('cart-product/filter', [CartProductController::class, 'filter']);
        Route::get('cart-product/{user_id}', [CartProductController::class, 'show']);
        Route::post('cart-product', [CartProductController::class, 'store']);
        Route::patch('cart-product/massUpdate/{user_id}', [CartProductController::class, 'massUpdate']);
    });
});

// Admin
Route::group(['middleware' => ['checkroleapi:admin']], function () {
    Route::group(['prefix' => 'v1', 'as' => 'v1.',], function () {
        // Mechanic
        Route::post('mechanic', [MechanicController::class, 'store']);
        Route::patch('mechanic/{id}', [MechanicController::class, 'update']);
        Route::delete('mechanic/{id}', [MechanicController::class, 'destroy']);
        Route::delete('mechanic/mass-delete', [MechanicController::class, 'massDestroy']);

        // Product
        Route::post('product', [ProductController::class, 'store']);
        Route::patch('product/{id}', [ProductController::class, 'update']);
        Route::delete('product/{id}', [ProductController::class, 'destroy']);
        Route::delete('product/mass-delete', [ProductController::class, 'massDestroy']);

        // Gallery
        Route::post('gallery', [GalleryController::class, 'store']);
        Route::patch('gallery/{id}', [GalleryController::class, 'update']);
        Route::delete('gallery/{id}', [GalleryController::class, 'destroy']);
        Route::delete('gallery/mass-delete', [GalleryController::class, 'massDestroy']);
    });
});

// Customer
Route::group(['middleware' => ['checkroleapi:customer']], function () {
    Route::group(['prefix' => 'v1', 'as' => 'v1.',], function () {
        // Route::post('mechanic', [MechanicController::class, 'store']);
        // Route::patch('mechanic/{id}', [MechanicController::class, 'update']);
        // Route::delete('mechanic/{id}', [MechanicController::class, 'destroy']);
        // Route::delete('mechanic/mass-delete', [MechanicController::class, 'massDestroy']);

        // Route::post('cart-product', [GalleryController::class, 'store']);
    });
});

// Guest
Route::group(['prefix' => 'v1',], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
});




// Route::middleware('auth:sanctum')->group(function () {

// Route::group(['prefix' => 'v1', 'as' => 'v1.',], function () {

//     Route::get('user', [UserController::class, 'showProfile']);
//     Route::patch('user/{id}/photo', [UserController::class, 'updatePhoto']);
//     Route::patch('user/{id}', [UserController::class, 'updateProfile']);
//     Route::post('logout', [UserController::class, 'logout']);

//     Route::get('mechanic/filter', [MechanicController::class, 'filter']);
//     Route::get('mechanic/{id}', [MechanicController::class, 'show']);

//     // Admin
//     Route::group(['middleware' => ['auth.jwt', 'role:admin']], function () {
//         Route::get('mechanic', [MechanicController::class, 'index']);
//         Route::post('mechanic', [MechanicController::class, 'store']);
//         Route::patch('mechanic/{id}', [MechanicController::class, 'update']);
//         Route::delete('mechanic/{id}', [MechanicController::class, 'destroy']);
//         Route::delete('mechanic/mass-delete', [MechanicController::class, 'massDestroy']);
//     });
// });
// });