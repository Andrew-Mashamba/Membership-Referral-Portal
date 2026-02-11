<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralStatusHistory extends Model
{
    protected $fillable = [
        'referral_id',
        'from_status',
        'to_status',
        'changed_by',
        'comment',
    ];

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
