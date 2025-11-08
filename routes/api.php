<?php

use App\Http\Controllers\Api\Fact\FactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/facts', [FactController::class, 'index']);
