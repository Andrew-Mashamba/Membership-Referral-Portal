<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\Setting;

class ReferralIdService
{
    public function generate(): string
    {
        $prefix = rtrim(Setting::get('referral_id_prefix', 'REF'), '-') . '-';
        $date = now()->format('Ymd');
        $last = Referral::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $seq = $last ? (int) substr($last->referral_id, -4) + 1 : 1;

        return $prefix . $date . '-' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
