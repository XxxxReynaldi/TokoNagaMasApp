<?php

use App\Http\Controllers\API\MechanicController;
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
    });
});

// Admin
Route::group(['middleware' => ['checkroleapi:admin']], function () {
    Route::group(['prefix' => 'v1', 'as' => 'v1.',], function () {
        Route::post('mechanic', [MechanicController::class, 'store']);
        Route::patch('mechanic/{id}', [MechanicController::class, 'update']);
        Route::delete('mechanic/{id}', [MechanicController::class, 'destroy']);
        Route::delete('mechanic/mass-delete', [MechanicController::class, 'massDestroy']);
    });
});

// Customer
Route::group(['middleware' => ['checkroleapi:customer']], function () {
    Route::group(['prefix' => 'v1', 'as' => 'v1.',], function () {
        // Route::post('mechanic', [MechanicController::class, 'store']);
        // Route::patch('mechanic/{id}', [MechanicController::class, 'update']);
        // Route::delete('mechanic/{id}', [MechanicController::class, 'destroy']);
        // Route::delete('mechanic/mass-delete', [MechanicController::class, 'massDestroy']);
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