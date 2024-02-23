<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserController;

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

// Open Routes of Authentication - doesnt need any token before accessing
Route::controller(ApiController::class)->group(function() {
    Route::post('/register', 'user_register');
    Route::post('/login', 'user_login');
});

// Protected Route for Authentication and Users Operations - needs token before accessing
Route::group([
    "middleware" => ["auth:api"]
], function(){

    Route::get('logout', [ApiController::class, 'user_logout']);

    Route::controller(UserController::class)->group(function() {
        Route::get('profile', 'user_profile');
        Route::put('update', 'user_update');
    });
});

// Protected and Admin role Routes
Route::group([
    "middleware" => ["auth:api", "adminauth"]
], function(){
    Route::controller(UserController::class)->group(function() {
        Route::delete('delete_user/{id}', 'user_delete');
        Route::put('update_role/{id}', 'update_role_admin');
    });
});

