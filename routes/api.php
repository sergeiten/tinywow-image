<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/session', [SessionController::class, 'get']);
Route::post('/{session}/upload', [ImageController::class, 'store']);
