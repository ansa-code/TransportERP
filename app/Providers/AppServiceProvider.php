<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Alert;

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
        if (config('app.debug')) {
    \Illuminate\Support\Facades\DB::listen(
        function ($query) {
            \Illuminate\Support\Facades\Log::info(
                sprintf('[%.1f ms] %s', $query->time, $query->sql)
            );
        }
    );
}
        View::composer('*', function ($view) {
            $unreadAlertsCount = 0;

            if (Auth::check()) {
                $unreadAlertsCount = Alert::where(
                    'status',
                    'unread'
                )->count();
            }

            $view->with(
                'unreadAlertsCount',
                $unreadAlertsCount
            );
        });
    }
}