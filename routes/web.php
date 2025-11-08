<?php

use App\Services\FactGenerationService;
use Illuminate\Support\Facades\Route;

Route::get('/', [FactGenerationService::class, 'generateFacts']);
