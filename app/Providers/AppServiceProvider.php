<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\AlertService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {

            $alertService = app(AlertService::class);

            // Generate latest alerts
            $alertService->generateAlerts();

            // Get unread alert count
            $alertCount = $alertService->unreadCount();

            $view->with('alertCount', $alertCount);
        });
    }
}
