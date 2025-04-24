<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Chuyển hướng dựa trên vai trò
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isReceptionist()) {
                return redirect()->route('receptionist.dashboard');
            } elseif ($user->isTechnician()) {
                return redirect()->route('technician.dashboard');
            } elseif ($user->isStaff()) {
                return redirect()->route('staff.dashboard');
            } elseif ($user->isCustomer()) {
                return redirect()->route('customer.dashboard');
            }
        }
        
        return $next($request);
    }
}
