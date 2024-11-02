<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if (Auth::guard('web')->check()) {
            $authenticatedUser = $request->user('web');
        } elseif (Auth::guard('admin')->check()) {
            $authenticatedUser = $request->user('admin');
        } else {
            $authenticatedUser = null;
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $authenticatedUser,
            ],
            'request' => [
                'urlParams' => $request->query(),
            ],
        ];
    }
}
