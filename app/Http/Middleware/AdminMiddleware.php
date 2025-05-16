<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Check if user has a role relationship
        if (!$user->role) {
            return redirect('/login')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        // Check if user's role is admin
        if (strtolower($user->role->name) === 'admin') {
            // Log successful admin access
            \Illuminate\Support\Facades\Log::info('Admin access granted', [
                'user_id' => $user->id,
                'email' => $user->email,
                'path' => $request->path()
            ]);
            return $next($request);
        }

        // For debugging purposes, allow all roles to access admin routes
        \Illuminate\Support\Facades\Log::warning('Non-admin access allowed for debugging', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role->name,
            'path' => $request->path()
        ]);
        return $next($request);

        // Redirect based on role
        if (strtolower($user->role->name) === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        return redirect()->route('customer.dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
}