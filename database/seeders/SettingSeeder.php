<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'referral_id_prefix' => 'REF',
            'lockout_attempts' => '5',
            'lockout_minutes' => '15',
            'session_timeout_minutes' => '120',
            'email_notifications_enabled' => '1',
            'sms_notifications_enabled' => '0',
        ];

        foreach ($defaults as $key => $value) {
            if (Setting::where('key', $key)->doesntExist()) {
                Setting::create(['key' => $key, 'value' => $value]);
            }
        }
    }
}
