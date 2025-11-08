<?php

use App\Http\Controllers\Fact\FactController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return "no welcome view";
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('registerForm');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('changePasswordForm');
Route::post('/password/change', [AuthController::class, 'changePassword'])->name('changePassword');

Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('updateProfile');


Route::get('/facts', [FactController::class, 'index']);
