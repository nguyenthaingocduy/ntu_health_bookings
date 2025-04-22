<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @param  string  $action
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission, string $action = 'view'): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if (!$user->hasPermission($permission, $action)) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }
        
        return $next($request);
    }
}
