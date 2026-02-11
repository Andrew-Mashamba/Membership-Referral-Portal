<x-guest-layout>
    <x-authentication-card>
        <h2 class="text-title font-semibold text-primaryText mb-4">Create an account</h2>
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-subtitle font-medium text-primaryText mb-1">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div>
                <label for="email" class="block text-subtitle font-medium text-primaryText mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div>
                <label for="membership_number" class="block text-subtitle font-medium text-primaryText mb-1">Membership number</label>
                <input id="membership_number" type="text" name="membership_number" value="{{ old('membership_number') }}" required autocomplete="off"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div>
                <label for="phone" class="block text-subtitle font-medium text-primaryText mb-1">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div>
                <label for="password" class="block text-subtitle font-medium text-primaryText mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div>
                <label for="password_confirmation" class="block text-subtitle font-medium text-primaryText mb-1">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="flex items-start min-h-[48px]">
                    <input id="terms" type="checkbox" name="terms" required
                        class="rounded border-brandGray/30 text-brandBlue focus:ring-brandBlue mt-1 w-5 h-5">
                    <label for="terms" class="ms-2 text-body text-secondaryText">
                        I agree to the <a href="{{ route('terms.show') }}" target="_blank" class="text-brandBlue hover:underline">Terms of Service</a>
                        and <a href="{{ route('policy.show') }}" target="_blank" class="text-brandBlue hover:underline">Privacy Policy</a>
                    </label>
                </div>
            @endif

            <div class="pt-2">
                <button type="submit" class="w-full min-h-[48px] px-4 bg-brandBlue text-white rounded-lgx font-semibold text-title focus:outline-none focus:ring-2 focus:ring-brandBlue focus:ring-offset-2 uppercase tracking-wide">
                    Register
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-body text-secondaryText hover:text-brandBlue focus:outline-none focus:ring-2 focus:ring-brandBlue rounded">
                    Already have an account? Log in
                </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
