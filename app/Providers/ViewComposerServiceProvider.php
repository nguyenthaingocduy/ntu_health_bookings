<?php

namespace App\Providers;

use App\Models\Clinic;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share clinics data with the service-popup component
        View::composer('components.service-popup', function ($view) {
            $view->with('clinics', Clinic::where('status', 'active')->get());
        });
    }
}
