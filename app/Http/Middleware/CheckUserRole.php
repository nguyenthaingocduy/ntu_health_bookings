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

        // Lấy vai trò từ bảng roles
        $userRole = \App\Models\Role::find($user->role_id);

        // Admin có thể truy cập tất cả các trang
        if ($userRole && strtolower($userRole->name) === 'admin') {
            return $next($request);
        }

        // Kiểm tra vai trò của người dùng
        if ($userRole && strtolower($userRole->name) === strtolower($role)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
}
