<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(?string $phone, string $message): void
    {
        // Only send if SMS notifications are enabled
        $enabled = Setting::get('sms_notifications_enabled');
        if ($enabled !== '1' || empty($phone)) {
            return;
        }

        $driver = config('sms.driver', 'log');
        $from = config('sms.from', 'ATCLSACCOS');

        // For now we support a simple log-based driver; real gateways
        // can be wired here (e.g. Twilio, Africa's Talking, etc.).
        if ($driver === 'log') {
            Log::info('SMS notification', [
                'to' => $phone,
                'from' => $from,
                'message' => $message,
            ]);
            return;
        }

        // Placeholder for custom gateway integration
        Log::warning('SMS driver not implemented', [
            'driver' => $driver,
            'to' => $phone,
            'from' => $from,
        ]);
    }
}

