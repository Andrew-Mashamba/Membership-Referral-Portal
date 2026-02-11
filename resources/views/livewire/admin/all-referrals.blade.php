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
  </style>

  <h1 class="text-2xl font-semibold text-primaryText mb-6 opacity-0 animate-fade-in-up delay-75">All referrals</h1>

  {{-- Filters + Export --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-6 opacity-0 animate-fade-in-up delay-150 transition-all duration-300 hover:shadow-card">
    <div class="flex flex-wrap gap-4 sm:gap-5 items-end">
      <div class="min-w-[140px]">
        <label for="all-status" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Status</label>
        <select id="all-status" wire:model.live="statusFilter"
                class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          <option value="">All</option>
          <option value="pending">Pending</option>
          <option value="in_review">In review</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
      <div>
        <label for="all-date-from" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">From date</label>
        <input id="all-date-from" type="date" wire:model.live="dateFrom"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div>
        <label for="all-date-to" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">To date</label>
        <input id="all-date-to" type="date" wire:model.live="dateTo"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div class="min-w-[180px]">
        <label for="all-referrer" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Referrer</label>
        <select id="all-referrer" wire:model.live="referrerId"
                class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          <option value="">All referrers</option>
          @foreach($referrerUsers as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
          @endforeach
        </select>
      </div>
      <a href="{{ route('admin.reports.export', ['dateFrom' => $dateFrom ?: null, 'dateTo' => $dateTo ?: null]) }}"
         class="min-h-[48px] px-5 py-3 bg-brandBlue text-white rounded-2xl shadow-soft font-semibold text-body flex items-center hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2 ml-auto">
        Export CSV
      </a>
    </div>
  </div>

  {{-- Table card --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden opacity-0 animate-fade-in-up delay-225 transition-all duration-300 hover:shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full text-body text-primaryText" role="table">
        <thead>
          <tr class="border-b border-brandGray/20 bg-brandGray/5">
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" scope="col" wire:click="sortBy('referral_id')">ID</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Referred name</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Contact</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" scope="col" wire:click="sortBy('status')">Status</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Referrer</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" scope="col" wire:click="sortBy('created_at')">Date</th>
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
              <td colspan="7" class="py-12 px-4 text-center text-body text-secondaryText">No referrals found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($referrals->hasPages())
      <div class="border-t border-brandGray/20 px-4 py-3 flex justify-center">
        {{ $referrals->links() }}
      </div>
    @endif
  </div>
</div>
