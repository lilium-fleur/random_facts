<?php

use App\Http\Controllers\Api\Fact\FactCommentController;
use App\Http\Controllers\Api\Fact\FactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/facts', [FactController::class, 'index']);

Route::get('/facts/{factId}/comments', [FactCommentController::class, 'index']);
Route::post('/facts/{factId}/comments', [FactCommentController::class, 'store']);
Route::put('/facts/{fact}/comments/{comment}', [FactCommentController::class, 'update']);
Route::delete('/facts/{fact}/comments/{comment}', [FactCommentController::class, 'destroy']);

