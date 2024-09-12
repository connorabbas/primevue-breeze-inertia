<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            $siteUrl = config('app.url');
            $userEmail = $notifiable->getEmailForPasswordReset();
            $resetPath = 'reset-password';
            if (is_a($notifiable, Admin::class)) {
                $resetPath = "admin/$resetPath";
            }

            return "$siteUrl/$resetPath/$token?email=$userEmail";
        });
    }
}
