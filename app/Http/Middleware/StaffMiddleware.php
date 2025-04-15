<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
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

        // Check if user's role is staff
        if (strtolower($user->role->name) === 'staff') {
            return $next($request);
        }

        // Redirect based on role
        if (strtolower($user->role->name) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
}
