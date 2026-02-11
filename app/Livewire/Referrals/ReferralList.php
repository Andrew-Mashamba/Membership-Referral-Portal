<?php

namespace App\Livewire\Referrals;

use App\Models\Referral;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class ReferralList extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
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
        $query = Referral::where('referrer_id', auth()->id())
            ->with('referrer');

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $referrals = $query->orderBy($this->sortField, $this->sortDir)
            ->paginate(10);

        return view('livewire.referrals.referral-list', [
            'referrals' => $referrals,
        ]);
    }
}
