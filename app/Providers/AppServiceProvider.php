<?php

namespace App\Providers;

use App\Listeners\LogLoginAttempt;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $listener = new LogLoginAttempt;
        Event::listen(Failed::class, [$listener, 'handleFailed']);
        Event::listen(Login::class, [$listener, 'handleLogin']);

        // Apply session timeout from Settings (STORY-004)
        try {
            $minutes = (int) (\App\Models\Setting::get('session_timeout_minutes') ?: Config::get('session.lifetime'));
            if ($minutes > 0) {
                Config::set('session.lifetime', $minutes);
            }
        } catch (\Throwable $e) {
            // Fallback to config-defined lifetime if settings are not available yet
        }
    }
}
