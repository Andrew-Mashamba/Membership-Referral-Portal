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
  </style>

  <h1 class="text-2xl font-semibold text-primaryText mb-8 opacity-0 animate-fade-in-up delay-75">Admin settings</h1>

  @if (session('message'))
    <div class="mb-8 p-4 rounded-xl bg-brandBlue/10 border border-brandBlue/20 text-body text-primaryText opacity-0 animate-fade-in-up delay-75">
      {{ session('message') }}
    </div>
  @endif

  <form wire:submit="save" class="opacity-0 animate-fade-in-up delay-150">
    {{-- Referral --}}
    <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 transition-all duration-300 hover:shadow-card mb-6">
      <h2 class="text-title font-semibold text-primaryText mb-4">Referral</h2>
      <div>
        <label for="referral_id_prefix" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Referral ID prefix</label>
        <input id="referral_id_prefix" type="text" wire:model="referral_id_prefix"
               class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
               placeholder="REF">
        @error('referral_id_prefix')
          <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
        @enderror
      </div>
    </div>

    

    {{-- Security --}}
    <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 transition-all duration-300 hover:shadow-card mb-6">
      <h2 class="text-title font-semibold text-primaryText mb-4">Security</h2>
      <div class="space-y-5">
        <div>
          <label for="lockout_attempts" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Lockout attempts (before account lock)</label>
          <input id="lockout_attempts" type="number" wire:model="lockout_attempts" min="1" max="20"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          @error('lockout_attempts')
            <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label for="lockout_minutes" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Lockout duration (minutes)</label>
          <input id="lockout_minutes" type="number" wire:model="lockout_minutes" min="1" max="1440"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          @error('lockout_minutes')
            <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label for="session_timeout_minutes" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Session timeout (minutes)</label>
          <input id="session_timeout_minutes" type="number" wire:model="session_timeout_minutes" min="5" max="1440"
                 class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          @error('session_timeout_minutes')
            <p class="text-subtitle text-secondaryText mt-1.5">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </div>

    {{-- Notifications --}}
    <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 transition-all duration-300 hover:shadow-card mb-6">
      <h2 class="text-title font-semibold text-primaryText mb-4">Notifications</h2>
      <div class="space-y-4">
        <label class="flex items-center gap-3 cursor-pointer group">
          <input type="checkbox" wire:model="email_notifications_enabled"
                 class="rounded border-brandGray/30 text-brandBlue focus:ring-2 focus:ring-brandBlue/30 w-5 h-5">
          <span class="text-body text-primaryText group-hover:text-brandBlue/80 transition-colors">Email notifications (approve/reject)</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer group">
          <input type="checkbox" wire:model="sms_notifications_enabled"
                 class="rounded border-brandGray/30 text-brandBlue focus:ring-2 focus:ring-brandBlue/30 w-5 h-5">
          <span class="text-body text-primaryText group-hover:text-brandBlue/80 transition-colors">SMS notifications (when applicable)</span>
        </label>
      </div>
    </div>

    <div class="mt-12 opacity-0 animate-fade-in-up delay-225 mb-6">
      <button type="submit"
              class="min-h-[48px] px-6 py-3 bg-brandBlue text-white rounded-2xl shadow-soft font-semibold text-body hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
        Save settings
      </button>
    </div>
  </form>
</div>
