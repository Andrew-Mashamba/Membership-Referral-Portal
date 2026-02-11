<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogLoginAttempt
{
    public function handleFailed(Failed $event): void
    {
        $identifier = $event->credentials['email'] ?? $event->credentials['membership_number'] ?? null;
        $this->log($identifier, false);

        if (! $identifier) {
            return;
        }

        // Per-account lockout after N failed attempts
        try {
            $user = User::where('email', $identifier)
                ->orWhere('membership_number', $identifier)
                ->first();
            if (! $user) {
                return;
            }

            $attemptsSetting = (int) (config('auth.lockout_attempts') ?? (\App\Models\Setting::get('lockout_attempts') ?: 5));
            $lockoutMinutes = (int) (\App\Models\Setting::get('lockout_minutes') ?: 15);

            $failedCount = DB::table('login_attempts')
                ->where('identifier', $identifier)
                ->where('success', false)
                ->where('attempted_at', '>=', now()->subMinutes($lockoutMinutes))
                ->count();

            if ($failedCount >= max(1, $attemptsSetting)) {
                $user->locked_until = now()->addMinutes($lockoutMinutes);
                $user->save();
                Log::warning('User account locked due to failed logins', [
                    'user_id' => $user->id,
                    'identifier' => $identifier,
                    'failed_count' => $failedCount,
                    'locked_until' => $user->locked_until,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error applying login lockout', ['error' => $e->getMessage()]);
        }
    }

    public function handleLogin(Login $event): void
    {
        $identifier = $event->user->email ?? $event->user->membership_number ?? null;
        $this->log($identifier, true);

        // Clear lockout on successful login
        if ($event->user->locked_until) {
            $event->user->locked_until = null;
            $event->user->save();
        }
    }

    private function log(?string $identifier, bool $success): void
    {
        if (! $identifier) {
            return;
        }
        try {
            DB::table('login_attempts')->insert([
                'identifier' => $identifier,
                'ip_address' => request()->ip(),
                'success' => $success,
                'attempted_at' => now(),
            ]);
        } catch (\Throwable) {
            // ignore
        }
    }
}
