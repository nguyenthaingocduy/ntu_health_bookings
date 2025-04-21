<?php

namespace App\Providers;

use App\Models\Contact;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load production-specific email configuration in production environment
        if ($this->app->environment('production')) {
            $productionConfig = require config_path('mail.production.php');
            config(['mail' => $productionConfig]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cấu hình URL scheme
        \Illuminate\Support\Facades\URL::forceScheme('http');

        // Share unread contact count with all views
        View::composer('layouts.admin', function ($view) {
            $view->with('unreadContactCount', Contact::where('is_read', false)->count());
        });

        // Thêm các cấu hình khác ở đây nếu cần
    }
}
