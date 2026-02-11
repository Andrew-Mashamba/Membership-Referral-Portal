<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        User::updateOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Test Member',
                'membership_number' => 'MEM001',
                'phone' => '+255712345001',
                'password' => $password,
                'role' => 'member',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'approver@example.com'],
            [
                'name' => 'Test Approver',
                'membership_number' => 'APR001',
                'phone' => '+255712345002',
                'password' => $password,
                'role' => 'approver',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Test Administrator',
                'membership_number' => 'ADM001',
                'phone' => '+255712345003',
                'password' => $password,
                'role' => 'administrator',
                'is_active' => true,
            ]
        );
    }
}
