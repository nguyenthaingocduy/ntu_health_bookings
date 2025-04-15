<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasRoleCheck
{
    protected function checkRole($roleName)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->role || strtolower($user->role->name) !== strtolower($roleName)) {
            if ($user->role && strtolower($user->role->name) === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role && strtolower($user->role->name) === 'customer') {
                return redirect()->route('customer.dashboard');
            }
            return redirect()->route('home');
        }

        return null;
    }
} 