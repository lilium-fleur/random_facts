<?php

use App\Http\Controllers\Fact\FactController;
use Illuminate\Support\Facades\Route;

Route::get('/facts', [FactController::class, 'index']);
