<div class="max-w-5xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
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

  <h1 class="text-2xl font-semibold text-primaryText mb-6 opacity-0 animate-fade-in-up delay-75">Pending referrals</h1>

  @if (session('message'))
    <div class="mb-6 p-4 rounded-xl bg-brandBlue/10 border border-brandBlue/20 text-body text-primaryText opacity-0 animate-fade-in-up delay-75">
      {{ session('message') }}
    </div>
  @endif

  {{-- Filters + bulk actions --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-6 opacity-0 animate-fade-in-up delay-150 transition-all duration-300 hover:shadow-card">
    <div class="flex flex-wrap gap-4 sm:gap-5 items-end">
      <div>
        <label for="pending-date-from" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">From date</label>
        <input id="pending-date-from" type="date" wire:model.live="dateFrom"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div>
        <label for="pending-date-to" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">To date</label>
        <input id="pending-date-to" type="date" wire:model.live="dateTo"
               class="rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div class="min-w-[180px]">
        <label for="pending-referrer" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Referrer</label>
        <select id="pending-referrer" wire:model.live="referrerId"
                class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          <option value="">All</option>
          @foreach($referrerUsers as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
          @endforeach
        </select>
      </div>
      @if (count($selected) > 0)
        <div class="flex flex-wrap gap-3 ml-auto">
          <button wire:click="bulkApprove" type="button"
                  class="min-h-[48px] px-5 py-3 bg-brandBlue text-white rounded-2xl shadow-soft font-semibold text-body hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Approve {{ count($selected) }} selected
          </button>
          <button wire:click="openBulkRejectModal" type="button"
                  class="min-h-[48px] px-5 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl font-semibold text-body hover:bg-brandGray/5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Reject {{ count($selected) }} selected
          </button>
        </div>
      @endif
    </div>
  </div>

  {{-- Table card --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden opacity-0 animate-fade-in-up delay-225 transition-all duration-300 hover:shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full text-body text-primaryText" role="table">
        <thead>
          <tr class="border-b border-brandGray/20 bg-brandGray/5">
            <th class="text-left py-3 px-4 w-12 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Select</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">ID</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Referred name</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Contact</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Referrer</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Date</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($referrals as $referral)
            <tr class="border-b border-brandGray/20 last:border-b-0 hover:bg-brandGray/5 transition-colors duration-200">
              <td class="py-3 px-4">
                @if($referral->isPending())
                  <input type="checkbox" wire:model="selected" value="{{ $referral->id }}"
                         class="rounded border-brandGray/30 text-brandBlue focus:ring-2 focus:ring-brandBlue/30 w-5 h-5"
                         aria-label="Select referral {{ $referral->referral_id }}">
                @endif
              </td>
              <td class="py-3 px-4">
                <a href="{{ route('admin.referrals.show', $referral) }}" class="font-medium text-brandBlue hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1 rounded-mdx">
                  {{ $referral->referral_id }}
                </a>
              </td>
              <td class="py-3 px-4">{{ $referral->referred_name }}</td>
              <td class="py-3 px-4 text-secondaryText">{{ $referral->referred_phone ?: $referral->referred_email ?: '–' }}</td>
              <td class="py-3 px-4">{{ $referral->referrer->name ?? '–' }}</td>
              <td class="py-3 px-4 text-secondaryText">{{ $referral->created_at->format('d M Y') }}</td>
              <td class="py-3 px-4">
                <div class="flex flex-wrap gap-2">
                  @if($referral->isPending())
                    <button wire:click="approve({{ $referral->id }})" type="button"
                            class="min-h-[44px] px-3 py-2 bg-brandBlue text-white rounded-xl text-body font-medium hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1">
                      Approve
                    </button>
                    <button wire:click="openRejectModal({{ $referral->id }})" type="button"
                            class="min-h-[44px] px-3 py-2 bg-primaryBg border border-brandGray/20 text-primaryText rounded-xl text-body font-medium hover:bg-brandGray/5 transition-colors focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1">
                      Reject
                    </button>
                  @endif
                  <a href="{{ route('admin.referrals.show', $referral) }}"
                     class="inline-flex items-center min-h-[44px] px-3 py-2 text-brandBlue text-body font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1 rounded-xl">
                    View
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-12 px-4 text-center text-body text-secondaryText">No pending referrals.</td>
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

  {{-- Single reject modal --}}
  @if ($showRejectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primaryText/50" wire:click.self="$set('showRejectModal', false)" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title">
      <div class="bg-white rounded-2xl shadow-card border border-white/80 p-5 sm:p-6 max-w-md w-full">
        <h2 id="reject-modal-title" class="text-title font-semibold text-primaryText mb-4">Reject referral</h2>
        <p class="text-body text-secondaryText mb-4">Rejection reason is required.</p>
        <label for="rejection-reason" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Reason</label>
        <textarea id="rejection-reason" wire:model="rejectionReason" rows="3"
                  class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[100px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none resize-y mb-4"
                  placeholder="Enter reason for rejection..."></textarea>
        @error('rejectionReason')
          <p class="text-subtitle text-secondaryText mb-3">{{ $message }}</p>
        @enderror
        <div class="flex gap-3">
          <button wire:click="reject" type="button"
                  class="flex-1 min-h-[48px] px-4 py-3 bg-brandBlue text-white rounded-2xl font-semibold text-body focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Confirm reject
          </button>
          <button wire:click="$set('showRejectModal', false)" type="button"
                  class="flex-1 min-h-[48px] px-4 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl font-semibold text-body hover:bg-brandGray/5 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Cancel
          </button>
        </div>
      </div>
    </div>
  @endif

  {{-- Bulk reject modal --}}
  @if ($showBulkRejectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primaryText/50" wire:click.self="$set('showBulkRejectModal', false)" role="dialog" aria-modal="true" aria-labelledby="bulk-reject-modal-title">
      <div class="bg-white rounded-2xl shadow-card border border-white/80 p-5 sm:p-6 max-w-md w-full">
        <h2 id="bulk-reject-modal-title" class="text-title font-semibold text-primaryText mb-4">Reject {{ count($selected) }} referrals</h2>
        <p class="text-body text-secondaryText mb-4">A shared rejection reason is required for all selected.</p>
        <label for="bulk-rejection-reason" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Reason</label>
        <textarea id="bulk-rejection-reason" wire:model="bulkRejectionReason" rows="3"
                  class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[100px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none resize-y mb-4"
                  placeholder="Enter reason for rejection..."></textarea>
        @error('bulkRejectionReason')
          <p class="text-subtitle text-secondaryText mb-3">{{ $message }}</p>
        @enderror
        <div class="flex gap-3">
          <button wire:click="bulkReject" type="button"
                  class="flex-1 min-h-[48px] px-4 py-3 bg-brandBlue text-white rounded-2xl font-semibold text-body focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Confirm reject all
          </button>
          <button wire:click="$set('showBulkRejectModal', false)" type="button"
                  class="flex-1 min-h-[48px] px-4 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl font-semibold text-body hover:bg-brandGray/5 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Cancel
          </button>
        </div>
      </div>
    </div>
  @endif
</div>
