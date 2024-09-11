<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as DefaultRedirectIfAuthenticated;

class RedirectIfAuthenticated extends DefaultRedirectIfAuthenticated
{
    /**
     * Get the path the user should be redirected to when they are authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (Auth::guard('admin')->check() || $request->is('admin/*')) {
            return route('admin.dashboard');
        }
        return route('dashboard');
    }
}
