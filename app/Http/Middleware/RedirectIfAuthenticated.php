<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Ghi log thông tin người dùng đã đăng nhập (chỉ trong môi trường debug)
                if (config('app.debug')) {
                    Log::info('RedirectIfAuthenticated: Người dùng đã đăng nhập', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role ? $user->role->name : 'No Role',
                        'session_id' => Session::getId(),
                        'url' => $request->url()
                    ]);
                }

                // Kiểm tra URL hiện tại để tránh chuyển hướng vô tận
                $currentUrl = $request->path();

                // Chỉ chuyển hướng nếu đang ở trang login hoặc register
                if ($request->is('login') || $request->is('register')) {
                    if (config('app.debug')) {
                        Log::info('RedirectIfAuthenticated: User is on login/register page, redirecting based on role', [
                            'user_id' => $user->id,
                            'current_url' => $currentUrl
                        ]);
                    }

                    // Chuyển hướng dựa vào vai trò
                    if ($user->role) {
                        $roleName = strtolower($user->role->name);

                        if ($roleName === 'admin') {
                            return redirect()->route('admin.dashboard');
                        } elseif ($roleName === 'staff') {
                            return redirect()->route('staff.dashboard');
                        } elseif ($roleName === 'receptionist') {
                            return redirect()->route('receptionist.dashboard');
                        } elseif ($roleName === 'technician') {
                            return redirect()->route('technician.dashboard');
                        } else {
                            return redirect()->route('customer.dashboard');
                        }
                    } else {
                        return redirect()->route('customer.dashboard');
                    }
                }

                // Nếu không phải trang login/register, cho phép tiếp tục
                return $next($request);
            }
        }

        return $next($request);
    }
}