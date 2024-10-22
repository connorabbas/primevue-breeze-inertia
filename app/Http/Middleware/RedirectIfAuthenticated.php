<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            Log::info('RedirectIfAuthenticated middleware called', [
                'guard' => $guard,
                'is_authenticated' => Auth::guard($guard)->check(),
                'is_admin_route' => $request->is('admin/*'),
            ]);

            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin' || $request->is('admin/*')) {
                    Log::info('Authenticated admin, redirecting to admin dashboard');
                    return redirect()->route('admin.dashboard');
                }

                Log::info('Authenticated user, redirecting to user dashboard');
                return redirect()->route('dashboard');
            }
        }

        Log::info('User not authenticated, proceeding with request');
        return $next($request);
    }
}
