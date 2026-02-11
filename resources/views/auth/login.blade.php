<x-guest-layout>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
      @theme {
        --color-clifford: #da373d;
      }
    </style>
    <x-authentication-card>
        <h2 class="text-title font-semibold text-primaryText mb-4">Sign in</h2>
        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 p-3 rounded-mdx bg-brandBlue/10 text-body text-primaryText">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-subtitle font-medium text-primaryText mb-1">Email or membership number</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div>
                <label for="password" class="block text-subtitle font-medium text-primaryText mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full rounded-mdx border border-brandGray/30 px-3 py-2.5 text-body text-primaryText focus:ring-2 focus:ring-brandBlue focus:border-brandBlue min-h-[48px]">
            </div>

            <div class="flex items-center min-h-[48px]">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-brandGray/30 text-brandBlue focus:ring-brandBlue w-5 h-5">
                <label for="remember_me" class="ms-2 text-body text-secondaryText">Remember me</label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full min-h-[48px] px-4 bg-brandBlue text-white rounded-lgx font-semibold text-title focus:outline-none focus:ring-2 focus:ring-brandBlue focus:ring-offset-2 uppercase tracking-wide">
                    Log in
                </button>
            </div>

            <div class="flex flex-wrap gap-3 justify-between text-body">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-brandBlue hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue rounded">
                        Create an account
                    </a>
                @endif
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-secondaryText hover:text-brandBlue focus:outline-none focus:ring-2 focus:ring-brandBlue rounded">
                        Forgot your password?
                    </a>
                @endif
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
