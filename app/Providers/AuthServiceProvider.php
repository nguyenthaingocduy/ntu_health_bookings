<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Định nghĩa các Gate cho các quyền
        Gate::before(function ($user) {
            // Admin có tất cả các quyền
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Định nghĩa các Gate cho các quyền cụ thể
        Gate::define('promotions.create', function ($user) {
            return $user->hasPermission('promotions.create');
        });

        Gate::define('promotions.edit', function ($user) {
            return $user->hasPermission('promotions.edit');
        });

        Gate::define('promotions.delete', function ($user) {
            return $user->hasPermission('promotions.delete');
        });

        Gate::define('promotions.view', function ($user) {
            return $user->hasPermission('promotions.view');
        });
    }
}
