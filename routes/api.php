<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Fact\FactCommentController;
use App\Http\Controllers\Api\Fact\FactController;
use App\Http\Controllers\Api\Fact\FactLikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/change', [AuthController::class, 'changePassword']);
});
Route::get('/facts', [FactController::class, 'index']);

Route::get('/facts/{factId}/comments', [FactCommentController::class, 'index']);
Route::post('/facts/{factId}/comments', [FactCommentController::class, 'store']);
Route::put('/facts/{fact}/comments/{comment}', [FactCommentController::class, 'update']);
Route::delete('/facts/{fact}/comments/{comment}', [FactCommentController::class, 'destroy']);

Route::post('/facts/{factId}/likes', [FactLikeController::class, 'store']);
Route::delete('/facts/{factId}/likes/{likeId}', [FactLikeController::class, 'destroy']);

