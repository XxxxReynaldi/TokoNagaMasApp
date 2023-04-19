<?php

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
Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'v1',], function () {
        Route::get('user', [UserController::class, 'showProfile']);
        Route::patch('user/{id}/photo', [UserController::class, 'updatePhoto']);
        Route::patch('user/{id}', [UserController::class, 'updateProfile']);
        Route::post('logout', [UserController::class, 'logout']);
    });
});

Route::group(['prefix' => 'v1',], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
});
