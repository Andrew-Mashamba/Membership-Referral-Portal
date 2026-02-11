<div class="max-w-2xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
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

  {{-- Header + status --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 opacity-0 animate-fade-in-up delay-75">
    <h1 class="text-2xl font-semibold text-primaryText">Referral {{ $referral->referral_id }}</h1>
    <span class="inline-flex items-center px-3 py-1.5 rounded-mdx text-subtitle font-medium w-fit
      @if($referral->status === 'approved') bg-brandBlue/10 text-brandBlue border border-brandBlue/20
      @elseif($referral->status === 'rejected') bg-brandGray/15 text-secondaryText border border-brandGray/20
      @else bg-brandBlue/5 text-primaryText border border-brandBlue/10
      @endif">
      {{ ucfirst(str_replace('_', ' ', $referral->status)) }}
    </span>
  </div>

  {{-- Details card --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-6 opacity-0 animate-fade-in-up delay-150 transition-all duration-300 hover:shadow-card">
    <h2 class="text-title font-semibold text-primaryText mb-4">Details</h2>
    <dl class="space-y-4 text-body text-primaryText">
      <div>
        <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Referred name</dt>
        <dd>{{ $referral->referred_name }}</dd>
      </div>
      <div>
        <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Phone</dt>
        <dd>{{ $referral->referred_phone ?: '–' }}</dd>
      </div>
      <div>
        <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Email</dt>
        <dd>{{ $referral->referred_email ?: '–' }}</dd>
      </div>
      <div>
        <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Relationship</dt>
        <dd>{{ $referral->relationship ?: '–' }}</dd>
      </div>
      @if($referral->notes)
        <div>
          <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Notes</dt>
          <dd class="whitespace-pre-wrap">{{ $referral->notes }}</dd>
        </div>
      @endif
      <div>
        <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Submitted</dt>
        <dd>{{ $referral->created_at->format('d M Y H:i') }}</dd>
      </div>
      @if($referral->rejection_reason)
        <div>
          <dt class="text-subtitle text-secondaryText uppercase tracking-wider mb-0.5">Rejection reason</dt>
          <dd class="text-secondaryText">{{ $referral->rejection_reason }}</dd>
        </div>
      @endif
    </dl>
  </div>

  {{-- Status history card --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-8 opacity-0 animate-fade-in-up delay-225 transition-all duration-300 hover:shadow-card">
    <h2 class="text-title font-semibold text-primaryText mb-4">Status history</h2>
    <ul class="space-y-3" role="list">
      @foreach ($referral->statusHistories as $h)
        <li class="py-2 px-4 rounded-xl hover:bg-brandGray/5 transition-colors duration-200 border-l-2 border-brandBlue/30 pl-4">
          <p class="text-body text-primaryText font-medium">
            {{ $h->from_status ? ucfirst(str_replace('_', ' ', $h->from_status)) : 'Created' }} → {{ ucfirst(str_replace('_', ' ', $h->to_status)) }}
          </p>
          <p class="text-subtitle text-secondaryText mt-0.5">{{ $h->created_at->format('d M Y H:i') }}@if($h->changedBy) · {{ $h->changedBy->name }}@endif</p>
          @if($h->comment)
            <p class="text-subtitle text-secondaryText mt-1">{{ $h->comment }}</p>
          @endif
        </li>
      @endforeach
    </ul>
  </div>

  {{-- Approver actions --}}
  @if(auth()->user()->isApprover() && $referral->isPending())
    <div class="flex flex-col sm:flex-row gap-3 mb-6 opacity-0 animate-fade-in-up delay-300">
      <button wire:click="approve" type="button"
              class="min-h-[48px] px-5 py-3 bg-brandBlue text-white rounded-2xl font-semibold text-body shadow-soft hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
        Approve
      </button>
      <button wire:click="openRejectModal" type="button"
              class="min-h-[48px] px-5 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl font-semibold text-body hover:bg-brandGray/5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
        Reject
      </button>
    </div>

    {{-- Reject modal --}}
    @if ($showRejectModal)
      <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primaryText/50" wire:click.self="$set('showRejectModal', false)" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title">
        <div class="bg-white rounded-2xl shadow-card border border-white/80 p-5 sm:p-6 max-w-md w-full">
          <h2 id="reject-modal-title" class="text-title font-semibold text-primaryText mb-4">Reject referral</h2>
          <label for="rejection-reason" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Reason (required)</label>
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
  @endif

  {{-- Back link --}}
  <div class="opacity-0 animate-fade-in-up delay-300">
    @if(request()->routeIs('admin.*'))
      <a href="{{ route('admin.referrals.pending') }}"
         class="inline-flex items-center min-h-[48px] px-5 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl shadow-soft font-semibold text-body hover:bg-brandGray/5 hover:border-brandGray/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
        ← Back to pending
      </a>
    @else
      <a href="{{ url()->previous() ?: route('referrals.index') }}"
         class="inline-flex items-center min-h-[48px] px-5 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl shadow-soft font-semibold text-body hover:bg-brandGray/5 hover:border-brandGray/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
        ← Back to my referrals
      </a>
    @endif
  </div>
</div>
