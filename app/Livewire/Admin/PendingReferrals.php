<?php

namespace App\Livewire\Admin;

use App\Models\Referral;
use App\Models\User;
use App\Notifications\ReferralStatusNotification;
use App\Services\SmsService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class PendingReferrals extends Component
{
    use WithPagination;

    public array $selected = [];
    public string $rejectionReason = '';
    public ?int $rejectingId = null;
    public bool $showRejectModal = false;
    public bool $showBulkRejectModal = false;
    public string $bulkRejectionReason = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public ?int $referrerId = null;

    public function approve(int $id): void
    {
        $referral = Referral::findOrFail($id);
        if (! $referral->isPending()) {
            return;
        }
        $oldStatus = $referral->status;

        $referral->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $referral->statusHistories()->create([
            'from_status' => $oldStatus,
            'to_status' => 'approved',
            'changed_by' => auth()->id(),
            'comment' => 'Approved',
        ]);

        $referral->referrer->notify(new ReferralStatusNotification($referral, 'approved'));
        app(SmsService::class)->send($referral->referrer->phone, "Your referral for {$referral->referred_name} ({$referral->referral_id}) has been approved.");
        session()->flash('message', 'Referral approved.');
    }

    public function openRejectModal(int $id): void
    {
        $this->rejectingId = $id;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function reject(): void
    {
        $this->validate(['rejectionReason' => 'required|string|max:500']);

        $referral = Referral::findOrFail($this->rejectingId);
        if (! $referral->isPending()) {
            $this->showRejectModal = false;
            return;
        }
        $oldStatus = $referral->status;

        $referral->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $referral->statusHistories()->create([
            'from_status' => $oldStatus,
            'to_status' => 'rejected',
            'changed_by' => auth()->id(),
            'comment' => $this->rejectionReason,
        ]);

        $referral->referrer->notify(new ReferralStatusNotification($referral, 'rejected'));
        app(SmsService::class)->send($referral->referrer->phone, "Your referral for {$referral->referred_name} ({$referral->referral_id}) was not approved." . ($referral->rejection_reason ? ' Reason: ' . $referral->rejection_reason : ''));

        $this->showRejectModal = false;
        $this->rejectingId = null;
        $this->rejectionReason = '';
        session()->flash('message', 'Referral rejected.');
    }

    public function openBulkRejectModal(): void
    {
        if (count($this->selected) === 0) {
            return;
        }
        $this->bulkRejectionReason = '';
        $this->showBulkRejectModal = true;
    }

    public function bulkReject(): void
    {
        $this->validate(['bulkRejectionReason' => 'required|string|max:500']);

        $referrals = Referral::whereIn('id', $this->selected)->whereIn('status', ['pending', 'in_review'])->get();
        foreach ($referrals as $referral) {
            $oldStatus = $referral->status;
            $referral->update([
                'status' => 'rejected',
                'rejection_reason' => $this->bulkRejectionReason,
            ]);
            $referral->statusHistories()->create([
                'from_status' => $oldStatus,
                'to_status' => 'rejected',
                'changed_by' => auth()->id(),
                'comment' => $this->bulkRejectionReason,
            ]);
            $referral->referrer->notify(new ReferralStatusNotification($referral, 'rejected'));
            app(SmsService::class)->send($referral->referrer->phone, "Your referral for {$referral->referred_name} ({$referral->referral_id}) was not approved." . ($referral->rejection_reason ? ' Reason: ' . $referral->rejection_reason : ''));
        }
        $count = count($referrals);
        $this->selected = [];
        $this->showBulkRejectModal = false;
        $this->bulkRejectionReason = '';
        session()->flash('message', $count . ' referral(s) rejected.');
    }

    public function bulkApprove(): void
    {
        $referrals = Referral::whereIn('id', $this->selected)->whereIn('status', ['pending', 'in_review'])->get();
        foreach ($referrals as $referral) {
            $oldStatus = $referral->status;
            $referral->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            $referral->statusHistories()->create([
                'from_status' => $oldStatus,
                'to_status' => 'approved',
                'changed_by' => auth()->id(),
                'comment' => 'Bulk approved',
            ]);
            $referral->referrer->notify(new ReferralStatusNotification($referral, 'approved'));
            app(SmsService::class)->send($referral->referrer->phone, "Your referral for {$referral->referred_name} ({$referral->referral_id}) has been approved.");
        }
        $this->selected = [];
        session()->flash('message', count($referrals) . ' referral(s) approved.');
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingReferrerId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Referral::whereIn('status', ['pending', 'in_review'])
            ->with('referrer')
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->referrerId, fn ($q) => $q->where('referrer_id', $this->referrerId))
            ->orderByDesc('created_at');

        $referrals = $query->paginate(10);
        $referrerUsers = User::whereIn('id', Referral::whereIn('status', ['pending', 'in_review'])->distinct()->pluck('referrer_id'))->orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.pending-referrals', [
            'referrals' => $referrals,
            'referrerUsers' => $referrerUsers,
        ]);
    }
}
