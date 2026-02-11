<x-guest-layout>
    <div class="w-full sm:max-w-md opacity-0 animate-fade-in-up">
        {{-- Glassmorphism card --}}
        <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/20 bg-white/95 backdrop-blur-xl px-6 py-8 sm:px-8 sm:py-10">
            <div class="space-y-6">
                <div class="text-center opacity-0 animate-fade-in-up delay-1">
                    <a href="{{ url('/') }}" class="inline-block focus:outline-none focus:ring-2 focus:ring-brandBlue/40 rounded-xl mb-4">
                        <img src="{{ asset('images/atcl-logo.png') }}" alt="ATCL SACCOS" class="h-18 sm:h-14 w-auto object-contain mx-auto rounded-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span class="text-xl font-semibold text-primaryText" style="display:none;">ATCL SACCOS</span>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-bold text-primaryText tracking-tight">Welcome back</h1>
                    <p class="text-body text-secondaryText mt-1">Sign in to your membership account</p>
                </div>

                <x-validation-errors class="mb-4 opacity-0 animate-fade-in-up delay-1" />

                @session('status')
                    <div class="p-3 rounded-xl bg-brandBlue/10 border border-brandBlue/20 text-body text-primaryText opacity-0 animate-fade-in-up delay-1">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="opacity-0 animate-fade-in-up delay-2 mb-6">
                        <label for="email" class="block text-subtitle font-medium text-primaryText mb-1.5">Email or membership number</label>
                        <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full rounded-xl border border-brandGray/25 bg-white/80 px-4 py-3 text-body text-primaryText placeholder:text-secondaryText/70 focus:ring-2 focus:ring-brandBlue/40 focus:border-brandBlue transition min-h-[48px]">
                    </div>

                    <div class="opacity-0 animate-fade-in-up delay-2 mb-6">
                        <label for="password" class="block text-subtitle font-medium text-primaryText mb-1.5">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full rounded-xl border border-brandGray/25 bg-white/80 px-4 py-3 text-body text-primaryText placeholder:text-secondaryText/70 focus:ring-2 focus:ring-brandBlue/40 focus:border-brandBlue transition min-h-[48px]">
                    </div>

                    <div class="flex items-center min-h-[44px] opacity-0 animate-fade-in-up delay-3 mb-6">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-brandGray/40 text-brandBlue focus:ring-brandBlue/50 w-5 h-5">
                        <label for="remember_me" class="ms-2 text-body text-secondaryText">Remember me</label>
                    </div>

                    <div class="pt-1 opacity-0 animate-fade-in-up delay-3 mb-6">
                        <button type="submit" class="w-full h-64 p-4 bg-brandBlue text-white rounded-xl font-semibold text-title focus:outline-none focus:ring-2 focus:ring-brandBlue/50 focus:ring-offset-2 hover:bg-[#1a4780] transition shadow-lg shadow-brandBlue/20">
                            Log in
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-between text-body pt-2 opacity-0 animate-fade-in-up delay-4 mb-6">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-brandBlue hover:underline focus:outline-none focus:ring-2 focus:ring-brandBlue/40 rounded">
                                Create an account
                            </a>
                        @endif
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-secondaryText hover:text-brandBlue focus:outline-none focus:ring-2 focus:ring-brandBlue/40 rounded">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
