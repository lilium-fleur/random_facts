<?php

namespace App\Providers;

use App\Services\Prompt\PromptToAiService;
use App\Services\Prompt\PromptToExternalAiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PromptToAiService::class, PromptToExternalAiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
