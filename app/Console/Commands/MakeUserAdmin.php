<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'user:admin {email? : User email or membership number}';

    protected $description = 'Set user role to administrator (by email or membership_number)';

    public function handle(): int
    {
        $email = $this->argument('email') ?? $this->ask('Enter user email or membership number');

        $user = User::where('email', $email)->orWhere('membership_number', $email)->first();

        if (! $user) {
            $this->error('User not found.');
            return 1;
        }

        $user->update(['role' => 'administrator']);
        $this->info("User {$user->email} is now an administrator.");
        return 0;
    }
}
