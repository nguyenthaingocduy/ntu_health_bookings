<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // Admin có tất cả các quyền
        if ($user->role && strtolower($user->role->name) === 'admin') {
            return $next($request);
        }

        // Kiểm tra quyền thông qua vai trò
        $hasPermission = false;

        if ($user->role) {
            $rolePermissions = $user->role->permissions()->pluck('name')->toArray();
            $hasPermission = in_array($permission, $rolePermissions);
        }

        if (!$hasPermission) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Bạn không có quyền thực hiện hành động này.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Bạn không có quyền truy cập trang này. Vui lòng liên hệ quản trị viên nếu bạn cần quyền này.');
        }

        return $next($request);
    }
}
