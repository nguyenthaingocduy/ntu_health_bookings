<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        
        // Admin có thể truy cập tất cả các trang
        if ($user->role && strtolower($user->role->name) === 'admin') {
            return $next($request);
        }
        
        // Kiểm tra vai trò của người dùng
        if (!$user->role || strtolower($user->role->name) !== strtolower($role)) {
            return redirect()->route('dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
        }
        
        return $next($request);
    }
}
