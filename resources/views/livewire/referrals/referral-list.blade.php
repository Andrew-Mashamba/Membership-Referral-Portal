<div class="max-w-4xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
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

  {{-- Page title + CTA --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 opacity-0 animate-fade-in-up delay-75">
    <h1 class="text-2xl font-semibold text-primaryText">My referrals</h1>
    <a href="{{ route('referrals.create') }}"
       class="inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[48px] bg-brandBlue text-white font-medium text-body rounded-2xl shadow-soft hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
      Submit referral
    </a>
  </div>

  {{-- Filters card --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-6 opacity-0 animate-fade-in-up delay-150 transition-all duration-300 hover:shadow-card">
    <h2 class="text-title font-semibold text-primaryText mb-4">Filter & sort</h2>
    <div class="flex flex-wrap gap-4 sm:gap-5 items-end">
      <div class="min-w-[160px]">
        <label for="status-filter" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Status</label>
        <select id="status-filter" wire:model.live="statusFilter"
                class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          <option value="">All statuses</option>
          <option value="pending">Pending</option>
          <option value="in_review">In review</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
      <div>
        <label for="date-from" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">From date</label>
        <input id="date-from" type="date" wire:model.live="dateFrom"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div>
        <label for="date-to" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">To date</label>
        <input id="date-to" type="date" wire:model.live="dateTo"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
    </div>
  </div>

  {{-- List card --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden opacity-0 animate-fade-in-up delay-225 transition-all duration-300 hover:shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full text-body text-primaryText" role="table">
        <thead>
          <tr class="border-b border-brandGray/20 bg-brandGray/5">
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" wire:click="sortBy('referral_id')">ID</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" wire:click="sortBy('referred_name')">Name</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider">Contact</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" wire:click="sortBy('created_at')">Date</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider cursor-pointer hover:text-primaryText transition-colors" wire:click="sortBy('status')">Status</th>
            <th class="text-left py-3 px-4 w-20" aria-label="View"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($referrals as $referral)
            <tr class="border-b border-brandGray/20 last:border-b-0 hover:bg-brandGray/5 transition-colors duration-200">
              <td class="py-3 px-4">
                <span class="font-medium text-brandBlue">{{ $referral->referral_id }}</span>
              </td>
              <td class="py-3 px-4">{{ $referral->referred_name }}</td>
              <td class="py-3 px-4 text-secondaryText">{{ $referral->referred_phone ?: $referral->referred_email ?: '–' }}</td>
              <td class="py-3 px-4 text-secondaryText">{{ $referral->created_at->format('d M Y') }}</td>
              <td class="py-3 px-4">
                <span class="inline-flex items-center px-3 py-1.5 rounded-mdx text-subtitle font-medium
                  @if($referral->status === 'approved') bg-brandBlue/10 text-brandBlue border border-brandBlue/20
                  @elseif($referral->status === 'rejected') bg-brandGray/15 text-secondaryText border border-brandGray/20
                  @else bg-brandBlue/5 text-primaryText border border-brandBlue/10
                  @endif">
                  {{ ucfirst(str_replace('_', ' ', $referral->status)) }}
                </span>
              </td>
              <td class="py-3 px-4">
                <a href="{{ route('referrals.show', $referral) }}"
                   class="inline-flex items-center min-h-[44px] px-3 text-body font-medium text-brandBlue hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1 rounded-mdx">
                  View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-12 px-4 text-center">
                <p class="text-body text-secondaryText mb-4">No referrals yet.</p>
                <a href="{{ route('referrals.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[48px] bg-brandBlue text-white font-medium text-body rounded-2xl shadow-soft hover:shadow-card transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
                  Submit your first referral
                </a>
              </td>
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
