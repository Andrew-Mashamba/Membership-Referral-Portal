<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_id',
        'referrer_id',
        'referred_name',
        'referred_phone',
        'referred_email',
        'relationship',
        'notes',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReferralStatusHistory::class)->orderByDesc('created_at');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending' || $this->status === 'in_review';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
