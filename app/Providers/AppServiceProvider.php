<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TfIdfService;
use App\Services\HybridRecommendationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register TfIdfService as singleton
        $this->app->singleton(TfIdfService::class, function ($app) {
            return new TfIdfService();
        });

        // Register HybridRecommendationService as singleton
        $this->app->singleton(HybridRecommendationService::class, function ($app) {
            return new HybridRecommendationService(
                $app->make(TfIdfService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
