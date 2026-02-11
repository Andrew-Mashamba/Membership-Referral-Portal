<div class="max-w-6xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
  <style>
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    .delay-75 { animation-delay: 75ms; }
    .delay-150 { animation-delay: 150ms; }
    .delay-225 { animation-delay: 225ms; }
    .delay-300 { animation-delay: 300ms; }
  </style>

  <h1 class="text-2xl font-semibold text-primaryText mb-8 opacity-0 animate-fade-in-up delay-75">Reports</h1>

  {{-- Date range + Export --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-8 opacity-0 animate-fade-in-up delay-150 transition-all duration-300 hover:shadow-card">
    <h2 class="text-title font-semibold text-primaryText mb-4">Date range</h2>
    <div class="flex flex-wrap gap-4 sm:gap-5 items-end mb-6">
      <div>
        <label for="reports-date-from" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">From date</label>
        <input id="reports-date-from" type="date" wire:model.live="dateFrom"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div>
        <label for="reports-date-to" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">To date</label>
        <input id="reports-date-to" type="date" wire:model.live="dateTo"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div>
        <label for="reports-period" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Preset</label>
        <select id="reports-period" wire:model.live="period"
                class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] min-w-[140px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          <option value="daily">Daily</option>
          <option value="monthly">Monthly</option>
          <option value="annual">Annual</option>
        </select>
      </div>
      <a href="{{ route('admin.reports.export', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]) }}"
         class="min-h-[48px] px-5 py-3 bg-brandBlue text-white rounded-2xl shadow-soft font-semibold text-body flex items-center hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2 ml-auto">
        Export CSV
      </a>
    </div>

    {{-- Summary stats (brand dark blue) --}}
    <h2 class="text-title font-semibold text-primaryText mb-4">Summary</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
      <div class="opacity-0 animate-fade-in-up delay-225 p-5 sm:p-6 bg-brandBlue rounded-2xl shadow-soft border border-brandBlue transition-all duration-300 hover:shadow-card hover:-translate-y-0.5">
        <p class="text-subtitle text-white uppercase tracking-wider mb-1">Total</p>
        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $total }}</p>
      </div>
      <a href="{{ route('admin.referrals.pending') }}" class="opacity-0 animate-fade-in-up delay-225 block p-5 sm:p-6 bg-brandBlue rounded-2xl shadow-soft border border-brandBlue transition-all duration-300 hover:shadow-card hover:-translate-y-0.5 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-white/30 focus:ring-offset-2 focus:ring-offset-brandBlue">
        <p class="text-subtitle text-white uppercase tracking-wider mb-1">Pending</p>
        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $pending }}</p>
      </a>
      <div class="opacity-0 animate-fade-in-up delay-300 p-5 sm:p-6 bg-brandBlue rounded-2xl shadow-soft border border-brandBlue transition-all duration-300 hover:shadow-card hover:-translate-y-0.5">
        <p class="text-subtitle text-white uppercase tracking-wider mb-1">Approved</p>
        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $approved }}</p>
      </div>
      <div class="opacity-0 animate-fade-in-up delay-300 p-5 sm:p-6 bg-brandBlue rounded-2xl shadow-soft border border-brandBlue transition-all duration-300 hover:shadow-card hover:-translate-y-0.5">
        <p class="text-subtitle text-white uppercase tracking-wider mb-1">Rejected</p>
        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $rejected }}</p>
      </div>
    </div>
  </div>

  {{-- Data table --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden opacity-0 animate-fade-in-up delay-300 transition-all duration-300 hover:shadow-card">
    <div class="p-5 sm:p-6">
      <h2 class="text-title font-semibold text-primaryText mb-4">Referrals in range</h2>
      <div class="overflow-x-auto">
        <table class="w-full text-body text-primaryText" role="table">
          <thead>
            <tr class="border-b border-brandGray/20 bg-brandGray/5">
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">ID</th>
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Referred name</th>
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Contact</th>
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Status</th>
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Referrer</th>
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Date</th>
              <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider w-20" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($referrals as $referral)
              <tr class="border-b border-brandGray/20 last:border-b-0 hover:bg-brandGray/5 transition-colors duration-200">
                <td class="py-3 px-4">
                  <a href="{{ route('admin.referrals.show', $referral) }}" class="font-medium text-brandBlue hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1 rounded-mdx">
                    {{ $referral->referral_id }}
                  </a>
                </td>
                <td class="py-3 px-4">{{ $referral->referred_name }}</td>
                <td class="py-3 px-4 text-secondaryText">{{ $referral->referred_phone ?: $referral->referred_email ?: '–' }}</td>
                <td class="py-3 px-4">
                  <span class="inline-flex items-center px-3 py-1.5 rounded-mdx text-subtitle font-medium
                    @if($referral->status === 'approved') bg-brandBlue/10 text-brandBlue border border-brandBlue/20
                    @elseif($referral->status === 'rejected') bg-brandGray/15 text-secondaryText border border-brandGray/20
                    @else bg-brandBlue/5 text-primaryText border border-brandBlue/10
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $referral->status)) }}
                  </span>
                </td>
                <td class="py-3 px-4">{{ $referral->referrer->name ?? '–' }}</td>
                <td class="py-3 px-4 text-secondaryText">{{ $referral->created_at->format('d M Y') }}</td>
                <td class="py-3 px-4">
                  <a href="{{ route('admin.referrals.show', $referral) }}"
                     class="inline-flex items-center min-h-[44px] px-3 text-body font-medium text-brandBlue hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1 rounded-mdx">
                    View
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-12 px-4 text-center text-body text-secondaryText">No referrals in this date range.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
