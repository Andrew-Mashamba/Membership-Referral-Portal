<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginAttemptSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('email', [
            'member@example.com',
            'approver@example.com',
            'admin@example.com',
        ])->get();

        if ($users->isEmpty()) {
            return;
        }

        $attempts = [];

        $baseDate = Carbon::today('UTC');

        foreach ($users as $user) {
            $identifier = $user->email;

            // Successful logins (most recent) - fixed timestamps for idempotency
            $attempts[] = [
                'identifier' => $identifier,
                'ip_address' => '127.0.0.1',
                'success' => true,
                'attempted_at' => $baseDate->copy()->subHours(2),
            ];
            $attempts[] = [
                'identifier' => $identifier,
                'ip_address' => '127.0.0.1',
                'success' => true,
                'attempted_at' => $baseDate->copy()->subDay(),
            ];
            $attempts[] = [
                'identifier' => $identifier,
                'ip_address' => '127.0.0.1',
                'success' => true,
                'attempted_at' => $baseDate->copy()->subDays(3),
            ];

            // Member: a few failed attempts (not enough to trigger lockout)
            if ($user->role === 'member') {
                $attempts[] = [
                    'identifier' => $identifier,
                    'ip_address' => '127.0.0.1',
                    'success' => false,
                    'attempted_at' => $baseDate->copy()->subDays(5),
                ];
                $attempts[] = [
                    'identifier' => $identifier,
                    'ip_address' => '192.168.1.100',
                    'success' => false,
                    'attempted_at' => $baseDate->copy()->subDays(5)->addMinutes(2),
                ];
            }
        }

        foreach ($attempts as $attempt) {
            $exists = DB::table('login_attempts')
                ->where('identifier', $attempt['identifier'])
                ->where('attempted_at', $attempt['attempted_at'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('login_attempts')->insert([
                'identifier' => $attempt['identifier'],
                'ip_address' => $attempt['ip_address'],
                'success' => $attempt['success'],
                'attempted_at' => $attempt['attempted_at'],
            ]);
        }
    }
}
