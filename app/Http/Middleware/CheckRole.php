<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            if (config('app.debug')) {
                Log::info('User not logged in');
            }
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();

        if (config('app.debug')) {
            Log::info('Checking role', [
                'user_id' => $user->id,
                'user_role_id' => $user->role_id,
                'required_role' => $role
            ]);
        }

        // Admin có thể truy cập tất cả các trang
        if ($user->role && strtolower($user->role->name) === 'admin') {
            return $next($request);
        }

        if (!$user->role) {
            if (config('app.debug')) {
                Log::error('User has no role relationship loaded', [
                    'user_id' => $user->id,
                    'role_id' => $user->role_id
                ]);
            }
            return redirect()->route('login')->with('error', 'Tài khoản của bạn chưa được gán vai trò. Vui lòng liên hệ quản trị viên.');
        }

        if (config('app.debug')) {
            Log::info('User role details', [
                'role_name' => $user->role->name,
                'required_role' => $role
            ]);
        }

        if (strtolower($user->role->name) !== strtolower($role)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Bạn không có quyền truy cập trang này.'], 403);
            }

            $roleName = strtolower($user->role->name);
            $errorMessage = 'Bạn đang cố truy cập trang không phù hợp với vai trò của bạn.';

            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', $errorMessage);
            } elseif ($roleName === 'staff') {
                return redirect()->route('staff.dashboard')->with('error', $errorMessage);
            } elseif ($roleName === 'receptionist') {
                return redirect()->route('receptionist.dashboard')->with('error', $errorMessage);
            } elseif ($roleName === 'technician') {
                return redirect()->route('nvkt.dashboard')->with('error', $errorMessage);
            } else {
                return redirect()->route('customer.dashboard')->with('error', $errorMessage);
            }
        }

        return $next($request);
    }
}
