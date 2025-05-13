<?php

use App\Http\Controllers\productController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [userController::class, 'register']);
Route::post('/verify', [userController::class, 'verify']);
Route::post('/login', [userController::class, 'login']);
Route::post('/product/create', [productController::class, 'create']);
