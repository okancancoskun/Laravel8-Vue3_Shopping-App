<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/currentUser', [AuthController::class, 'currentUser'])->middleware('auth:api');
Route::middleware('api')->group(function () {
    Route::post('user/assignRole', [UserController::class, 'assignRole']);
});
Route::group(['prefix' => 'cart', 'middleware' => ['auth:api']], function () {
    Route::post('addItem', [CartController::class, 'addItem']);
    Route::get('getCart/{userId}', [CartController::class, 'getCartByUserId']);
    Route::get('clearCart', [CartController::class, 'clearCart']);
    Route::post('removeItem', [CartController::class, 'removeItem']);
    Route::post('decreaseItem', [CartController::class, 'decreaseItemByOne']);
});
Route::group(['prefix' => 'order', 'middleware' => ['auth:api']], function () {
    Route::get('', [OrderController::class, 'findAll']);
    Route::get('{id}', [OrderController::class, 'findOne']);
    Route::post('create', [OrderController::class, 'create']);
    Route::get('findByUserId/{userId}', [OrderController::class, 'findOrdersByUserId']);
});

Route::prefix('category')->group(function () {
    Route::get('', [CategoryController::class, 'findAll']);
    Route::post('create', [CategoryController::class, 'create'])->middleware('auth:api');
});
Route::prefix('role')->group(function () {
    Route::get('', [RoleController::class, 'findAll']);
    Route::post('create', [RoleController::class, 'create']);
});

Route::prefix('product')->group(function () {
    Route::post('create', [ProductController::class, 'create'])->middleware('auth:api')/* ->middleware('role:admin') */;
    Route::get('', [ProductController::class, 'findAll']);
    Route::get('{id}', [ProductController::class, 'findOne']);
    Route::put('{product}', [ProductController::class, 'updateOne'])->middleware('auth:api');
    Route::delete('{product}', [ProductController::class, 'deleteOne'])->middleware('auth:api');
    Route::get('product/category/{category}', [ProductController::class, 'findByCategory']);
    Route::put('uploadFile/{id}', [ProductController::class, 'uploadFile']);
});
