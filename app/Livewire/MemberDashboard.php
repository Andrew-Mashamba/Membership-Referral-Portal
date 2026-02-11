<?php

namespace App\Livewire;

use App\Models\Referral;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class MemberDashboard extends Component
{
    public string $period = 'monthly'; // daily, monthly, annual

    /** Return time-series chart data for current period (member's referrals only). */
    public function getChartTimeData(): array
    {
        $userId = auth()->id();
        $now = now();
        if ($this->period === 'daily') {
            $from = $now->copy()->subDays(30)->startOfDay();
            $groupCallback = fn ($r) => $r->created_at->format('Y-m-d');
        } elseif ($this->period === 'annual') {
            $from = $now->copy()->subYears(5)->startOfYear();
            $groupCallback = fn ($r) => $r->created_at->format('Y');
        } else {
            $from = $now->copy()->subMonths(6)->startOfMonth();
            $groupCallback = fn ($r) => $r->created_at->format('Y-m');
        }

        $referralsOverTime = Referral::where('referrer_id', $userId)
            ->where('created_at', '>=', $from)
            ->get()
            ->groupBy($groupCallback)
            ->map(fn ($group) => $group->count());

        $labels = $referralsOverTime->keys()->sort()->values()->all();
        $values = collect($labels)->map(fn ($key) => $referralsOverTime->get($key, 0))->all();

        return ['labels' => $labels, 'values' => $values];
    }

    public function render()
    {
        $userId = auth()->id();
        $total = Referral::where('referrer_id', $userId)->count();
        $pending = Referral::where('referrer_id', $userId)->whereIn('status', ['pending', 'in_review'])->count();
        $approved = Referral::where('referrer_id', $userId)->where('status', 'approved')->count();
        $rejected = Referral::where('referrer_id', $userId)->where('status', 'rejected')->count();
        $recent = Referral::where('referrer_id', $userId)->orderByDesc('created_at')->take(5)->get();

        $now = now();
        if ($this->period === 'daily') {
            $from = $now->copy()->subDays(30)->startOfDay();
            $groupCallback = fn ($r) => $r->created_at->format('Y-m-d');
        } elseif ($this->period === 'annual') {
            $from = $now->copy()->subYears(5)->startOfYear();
            $groupCallback = fn ($r) => $r->created_at->format('Y');
        } else {
            $from = $now->copy()->subMonths(6)->startOfMonth();
            $groupCallback = fn ($r) => $r->created_at->format('Y-m');
        }

        $referralsOverTime = Referral::where('referrer_id', $userId)
            ->where('created_at', '>=', $from)
            ->get()
            ->groupBy($groupCallback)
            ->map(fn ($group) => $group->count());

        $chartTimeLabels = $referralsOverTime->keys()->sort()->values()->all();
        $chartTimeValues = collect($chartTimeLabels)->map(fn ($key) => $referralsOverTime->get($key, 0))->all();
        $chartStatusLabels = ['Pending', 'Approved', 'Rejected'];
        $chartStatusValues = [$pending, $approved, $rejected];

        return view('livewire.member-dashboard', [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'recent' => $recent,
            'referralsOverTime' => $referralsOverTime,
            'period' => $this->period,
            'chartTimeLabels' => $chartTimeLabels,
            'chartTimeValues' => $chartTimeValues,
            'chartStatusLabels' => $chartStatusLabels,
            'chartStatusValues' => $chartStatusValues,
        ]);
    }
}
