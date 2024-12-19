<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderPayment;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route for foods   
Route::get('/foods', [FoodController::class, 'index']);
Route::get('/foods/{id}', [FoodController::class, 'show']);

//Route for admins
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('/admin')->group(function () {
        Route::resource('/foods', FoodController::class);
        Route::resource('/orders', OrdersController::class);
        Route::resource('/payments', PaymentController::class);
    });
});

//Route for orders
Route::resource('/orders', OrdersController::class)->except(
    ['create','edit','delete']
);

//Route for order payments
Route::resource('/payments', OrderPayment::class);

//Route for registering & logging in
Route::post('/register', [AccountController::class, 'register']);
Route::post('/login', [AccountController::class, 'login']);

//Private routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AccountController::class, 'logout']);   
});