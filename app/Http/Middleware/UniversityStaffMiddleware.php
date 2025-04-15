<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UniversityStaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if the user is a staff member and has university staff information
        if (!$user->isStaff() || !$user->isUniversityStaff()) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Bạn không có quyền truy cập tính năng này. Chỉ cán bộ viên chức Trường Đại học Nha Trang mới có thể đặt lịch khám sức khỏe.');
        }

        return $next($request);
    }
}
