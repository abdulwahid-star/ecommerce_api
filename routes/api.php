<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });
});

Route::group([
    // 'middleware' => ['auth:api'],
    'prefix' => 'brands'
], function($router) {
    Route::get('/index', [BrandsController::class, 'index']);
    Route::get('/show/{id}', [BrandsController::class, 'show']);
    Route::post('/store', [BrandsController::class, 'store']);
    Route::put('/update-brand/{id}', [BrandsController::class, 'updateBrand']);
    Route::delete('/delete-brand/{id}', [BrandsController::class, 'destroy']);
});

Route::group([
    // 'middleware' => ['auth:api'],
    'prefix' => 'category'
], function($router) {
    Route::get('/index', [CategoryController::class, 'index']);
    Route::get('/show/{id}', [CategoryController::class, 'show']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::put('/update-category/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);
});

Route::group([
    // 'middleware' => ['auth:api'],
    'prefix' => 'location'
], function($router) {
    Route::post('/store', [LocationController::class, 'store']);
    Route::put('/update-location/{id}', [LocationController::class, 'updateLocation']);
    Route::delete('/delete-location/{id}', [LocationController::class, 'destroy']);
});

Route::group([
    'middleware' => ['auth:api'],
    'prefix' => 'product'
], function($router) {
    Route::get('/index', [ProductController::class, 'index']);
    Route::get('/show/{id}', [ProductController::class, 'show']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::put('/update-category/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/delete-category/{id}', [ProductController::class, 'destroy']);
});

