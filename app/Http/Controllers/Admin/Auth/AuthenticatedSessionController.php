<?php

namespace App\Http\Controllers\Admin\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        Log::info('Admin login view displayed');
        return Inertia::render('Admin/Auth/Login', [
            'canResetPassword' => Route::has('admin.password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('Admin login attempt', ['email' => $request->email]);

        try {
            $request->authenticate();
            $request->session()->regenerate();

            Log::info('Admin authenticated successfully', ['email' => $request->email]);

            return redirect()->intended(route('admin.dashboard'));
        } catch (\Exception $e) {
            Log::error('Admin authentication failed', ['email' => $request->email, 'error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('Admin logout', ['user' => Auth::guard('admin')->user()->email]);

        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
