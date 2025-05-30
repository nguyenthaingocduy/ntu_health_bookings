<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'user.role' => \App\Http\Middleware\CheckUserRole::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'technician' => \App\Http\Middleware\TechnicianMiddleware::class,
            'receptionist' => \App\Http\Middleware\ReceptionistMiddleware::class,
            'staff' => \App\Http\Middleware\StaffMiddleware::class,
            'university.staff' => \App\Http\Middleware\UniversityStaffMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
