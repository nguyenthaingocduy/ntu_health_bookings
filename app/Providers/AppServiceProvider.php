<?php

namespace App\Providers;

use App\Helpers\TimeHelper;
use App\Models\Contact;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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

        // Đăng ký directive Blade để định dạng thời gian
        Blade::directive('formatTime', function ($expression) {
            return "<?php echo App\\Helpers\\TimeHelper::formatTime($expression); ?>";
        });

        // Đăng ký directive Blade để định dạng khoảng thời gian
        Blade::directive('formatTimeRange', function ($expression) {
            return "<?php echo App\\Helpers\\TimeHelper::formatTimeRange($expression); ?>";
        });

        // Thêm các cấu hình khác ở đây nếu cần
    }
}
