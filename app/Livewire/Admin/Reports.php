<?php

namespace App\Livewire\Admin;

use App\Models\Referral;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Reports extends Component
{
    public string $period = 'monthly'; // daily, monthly, annual
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $from = $this->dateFrom ? Carbon::parse($this->dateFrom)->startOfDay() : now()->startOfMonth();
        $to = $this->dateTo ? Carbon::parse($this->dateTo)->endOfDay() : now();

        $base = fn () => Referral::whereBetween('created_at', [$from, $to]);
        $total = $base()->count();
        $pending = $base()->whereIn('status', ['pending', 'in_review'])->count();
        $approved = $base()->where('status', 'approved')->count();
        $rejected = $base()->where('status', 'rejected')->count();

        $referrals = Referral::whereBetween('created_at', [$from, $to])
            ->with('referrer')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.admin.reports', [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'referrals' => $referrals,
        ]);
    }
}
