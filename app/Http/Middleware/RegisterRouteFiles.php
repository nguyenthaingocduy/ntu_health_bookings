<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterRouteFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ kiểm tra các file route trong môi trường debug
        if (config('app.debug')) {
            // This middleware ensures that all route files are registered
            // Main routes are included in web.php and admin.php
            // Additional admin routes are included in admin.php

            // List of route files that should be registered
            $routeFiles = [
                'web.php',
                'admin.php',
                'staff.php',
                'admin/invoices.php',
                'admin/posts.php',
                'admin/promotions.php',
                'admin/settings.php',
                'admin/permissions.php',
                'admin/roles.php',
            ];

            // Check if all route files exist
            foreach ($routeFiles as $file) {
                $path = base_path('routes/' . $file);
                if (!file_exists($path)) {
                    // Log warning if a route file is missing
                    \Illuminate\Support\Facades\Log::warning('Route file not found: ' . $file);
                }
            }
        }

        return $next($request);
    }
}
