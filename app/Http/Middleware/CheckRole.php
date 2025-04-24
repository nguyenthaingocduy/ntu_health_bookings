<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (!$request->user()) {
            if (config('app.debug')) {
                Log::info('User not logged in');
            }
            return redirect()->route('login');
        }

        if (config('app.debug')) {
            Log::info('Checking role', [
                'user_id' => $request->user()->id,
                'user_role_id' => $request->user()->role_id,
                'required_role' => $role
            ]);
        }

        if (!$request->user()->role) {
            if (config('app.debug')) {
                Log::error('User has no role relationship loaded', [
                    'user_id' => $request->user()->id,
                    'role_id' => $request->user()->role_id
                ]);
            }
            return redirect()->route('login');
        }

        if (config('app.debug')) {
            Log::info('User role details', [
                'role_name' => $request->user()->role->name,
                'required_role' => $role
            ]);
        }

        if (strtolower($request->user()->role->name) !== strtolower($role)) {
            if ($request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($request->user()->isStaff()) {
                return redirect()->route('staff.dashboard');
            } elseif ($request->user()->isReceptionist()) {
                return redirect()->route('receptionist.dashboard');
            } elseif ($request->user()->isTechnician()) {
                return redirect()->route('technician.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }

        return $next($request);
    }
}
