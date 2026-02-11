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

  <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden transition-all duration-300 hover:shadow-card opacity-0 animate-fade-in-up delay-75">
    <div class="p-5 sm:p-6">
      <h1 class="text-2xl font-semibold text-primaryText mb-2">Submit a referral</h1>
      <p class="text-body text-secondaryText mb-6">Enter the details of the person you are referring for membership.</p>

      @if (session('message'))
        <div class="mb-6 p-4 rounded-xl bg-brandBlue/10 border border-brandBlue/20 text-body text-primaryText">
          {{ session('message') }}
        </div>
      @endif

      <form wire:submit="submit" class="space-y-6">
        <div class="opacity-0 animate-fade-in-up delay-150">
          <label for="referred_name" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Full name <span class="text-primaryText">*</span></label>
          <input type="text" id="referred_name" wire:model="referred_name"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                 required
                 autofocus>
          @error('referred_name')
            <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div class="opacity-0 animate-fade-in-up delay-150">
          <label for="referred_phone" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Phone</label>
          <input type="text" id="referred_phone" wire:model="referred_phone"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                 placeholder="+255...">
          @error('referred_phone')
            <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div class="opacity-0 animate-fade-in-up delay-225">
          <label for="referred_email" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Email</label>
          <input type="email" id="referred_email" wire:model="referred_email"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                 placeholder="name@example.com">
          @error('referred_email')
            <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <p class="text-body text-secondaryText -mt-1 opacity-0 animate-fade-in-up delay-225">Provide at least one of phone or email.</p>

        <div class="opacity-0 animate-fade-in-up delay-300">
          <label for="relationship" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Relationship to you</label>
          <input type="text" id="relationship" wire:model="relationship"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                 placeholder="e.g. Colleague, Friend, Relative">
        </div>

        <div class="opacity-0 animate-fade-in-up delay-300">
          <label for="notes" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Notes</label>
          <textarea id="notes" wire:model="notes" rows="3"
                    class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[100px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none resize-y"
                    placeholder="Optional notes about the referral"></textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2 opacity-0 animate-fade-in-up delay-300">
          <button type="submit"
                  class="flex-1 min-h-[48px] sm:min-h-[72px] px-5 py-3 bg-brandBlue text-white rounded-2xl shadow-soft font-semibold text-body hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Submit referral
          </button>
          <a href="{{ route('referrals.index') }}"
             class="flex-1 min-h-[48px] sm:min-h-[72px] px-5 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl shadow-soft font-semibold text-body flex items-center justify-center hover:bg-brandGray/5 hover:border-brandGray/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
