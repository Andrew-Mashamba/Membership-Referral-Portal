<?php

namespace App\Livewire\Admin;

use App\Models\Referral;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class AllReferrals extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public ?int $referrerId = null;
    public string $sortField = 'created_at';
    public string $sortDir = 'desc';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
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

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDir = 'desc';
        }
    }

    public function render()
    {
        $query = Referral::with('referrer')
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->referrerId, fn ($q) => $q->where('referrer_id', $this->referrerId))
            ->orderBy($this->sortField, $this->sortDir);

        $referrals = $query->paginate(15);
        $referrers = Referral::query()->distinct()->pluck('referrer_id')->filter()->toArray();
        $referrerUsers = $referrers ? \App\Models\User::whereIn('id', $referrers)->orderBy('name')->get(['id', 'name']) : collect();

        return view('livewire.admin.all-referrals', [
            'referrals' => $referrals,
            'referrerUsers' => $referrerUsers,
        ]);
    }
}
