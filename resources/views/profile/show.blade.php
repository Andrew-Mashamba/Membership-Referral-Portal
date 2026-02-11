<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-primaryText leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')

            <x-section-border />
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="mt-12 sm:mt-0">
                @livewire('profile.update-password-form')
            </div>

            <x-section-border />
        @endif

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div class="mt-12 sm:mt-0">
                @livewire('profile.two-factor-authentication-form')
            </div>

            <x-section-border />
        @endif

        <div class="mt-12 sm:mt-0">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <x-section-border />

            <div class="mt-12 sm:mt-0">
                @livewire('profile.delete-user-form')
            </div>
        @endif
    </div>
</x-app-layout>
