<?php

namespace App\Livewire\Referrals;

use App\Models\Referral;
use App\Notifications\ReferralStatusNotification;
use App\Services\SmsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class ReferralDetail extends Component
{
    public Referral $referral;
    public bool $showRejectModal = false;
    public string $rejectionReason = '';

    public function mount(Referral $referral): void
    {
        $this->referral = $referral;

        if (! auth()->user()->isApprover() && $referral->referrer_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->unreadNotifications()
            ->where('data->referral_id', $referral->referral_id)
            ->update(['read_at' => now()]);
    }

    public function approve(): void
    {
        if (! auth()->user()->isApprover() || ! $this->referral->isPending()) {
            return;
        }
        $fromStatus = $this->referral->status;
        $this->referral->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        $this->referral->statusHistories()->create([
            'from_status' => $fromStatus,
            'to_status' => 'approved',
            'changed_by' => auth()->id(),
            'comment' => 'Approved',
        ]);
        $this->referral->referrer->notify(new ReferralStatusNotification($this->referral, 'approved'));
        app(SmsService::class)->send($this->referral->referrer->phone, "Your referral for {$this->referral->referred_name} ({$this->referral->referral_id}) has been approved.");
        session()->flash('message', 'Referral approved.');
        $this->redirect(route('admin.referrals.pending'), navigate: true);
    }

    public function openRejectModal(): void
    {
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function reject(): void
    {
        $this->validate(['rejectionReason' => 'required|string|max:500']);
        if (! auth()->user()->isApprover() || ! $this->referral->isPending()) {
            $this->showRejectModal = false;
            return;
        }
        $this->referral->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);
        $this->referral->statusHistories()->create([
            'from_status' => 'pending',
            'to_status' => 'rejected',
            'changed_by' => auth()->id(),
            'comment' => $this->rejectionReason,
        ]);
        $this->referral->referrer->notify(new ReferralStatusNotification($this->referral, 'rejected'));
        app(SmsService::class)->send($this->referral->referrer->phone, "Your referral for {$this->referral->referred_name} ({$this->referral->referral_id}) was not approved." . ($this->referral->rejection_reason ? ' Reason: ' . $this->referral->rejection_reason : ''));
        $this->showRejectModal = false;
        $this->rejectionReason = '';
        session()->flash('message', 'Referral rejected.');
        $this->redirect(route('admin.referrals.pending'), navigate: true);
    }

    public function render()
    {
        $this->referral->load('statusHistories.changedBy');
        return view('livewire.referrals.referral-detail');
    }
}
