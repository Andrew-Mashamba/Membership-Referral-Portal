<?php

namespace App\Livewire\Admin;

use App\Models\Referral;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class Dashboard extends Component
{
    public string $period = 'monthly'; // daily, monthly, annual

    /** Return time-series chart data for current period (for JS chart updates). */
    public function getChartTimeData(): array
    {
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

        $referralsOverTime = Referral::query()
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
        $total = Referral::count();
        $pending = Referral::whereIn('status', ['pending', 'in_review'])->count();
        $approved = Referral::where('status', 'approved')->count();
        $rejected = Referral::where('status', 'rejected')->count();
        $topReferrers = Referral::query()
            ->selectRaw('referrer_id, count(*) as cnt')
            ->groupBy('referrer_id')
            ->orderByDesc('cnt')
            ->take(5)
            ->with('referrer:id,name,email')
            ->get();

        // Trend over time with optional period (STORY-014)
        $now = now();
        if ($this->period === 'daily') {
            $from = $now->copy()->subDays(30)->startOfDay();
            $groupCallback = fn ($r) => $r->created_at->format('Y-m-d');
        } elseif ($this->period === 'annual') {
            $from = $now->copy()->subYears(5)->startOfYear();
            $groupCallback = fn ($r) => $r->created_at->format('Y');
        } else { // monthly
            $from = $now->copy()->subMonths(6)->startOfMonth();
            $groupCallback = fn ($r) => $r->created_at->format('Y-m');
        }

        $referralsOverTime = Referral::query()
            ->where('created_at', '>=', $from)
            ->get()
            ->groupBy($groupCallback)
            ->map(fn ($group) => $group->count());

        // Chart-ready: time series (sorted labels + values)
        $timeLabels = $referralsOverTime->keys()->sort()->values()->all();
        $timeValues = collect($timeLabels)->map(fn ($key) => $referralsOverTime->get($key, 0))->all();

        // Chart-ready: status breakdown for doughnut
        $statusLabels = ['Pending', 'Approved', 'Rejected'];
        $statusValues = [$pending, $approved, $rejected];

        // Chart-ready: top referrers (names + counts)
        $referrerNames = $topReferrers->map(fn ($r) => $r->referrer->name ?? 'Unknown')->all();
        $referrerCounts = $topReferrers->map(fn ($r) => $r->cnt)->all();

        return view('livewire.admin.dashboard', [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'topReferrers' => $topReferrers,
            'referralsOverTime' => $referralsOverTime,
            'period' => $this->period,
            'chartTimeLabels' => $timeLabels,
            'chartTimeValues' => $timeValues,
            'chartStatusLabels' => $statusLabels,
            'chartStatusValues' => $statusValues,
            'chartReferrerLabels' => $referrerNames,
            'chartReferrerValues' => $referrerCounts,
        ]);
    }
}
