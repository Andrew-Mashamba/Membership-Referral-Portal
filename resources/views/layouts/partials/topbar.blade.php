<header class="shrink-0 min-h-16 py-2 flex items-center justify-between px-4 sm:px-6 bg-white border-b border-gray-100">
    {{-- Left: mobile menu button (desktop has sidebar) --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true"
                class="lg:hidden p-2 rounded-mdx text-secondaryText hover:bg-brandGray/5 hover:text-primaryText min-h-[48px] min-w-[48px] flex items-center justify-center"
                aria-label="Open menu">
            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="block">
            <div class="text-title font-semibold text-primaryText leading-tight">ATCL SACCOS</div>
            <div class="text-subtitle text-secondaryText leading-tight">Membership Referral Portal</div>
        </div>
    </div>

    {{-- Right: notifications + user menu --}}
    <div class="flex items-center gap-2 sm:gap-3">
        @livewire('notification-dropdown')

        <x-dropdown align="right" width="48" contentClasses="py-1 bg-white rounded-mdx shadow-soft ring-1 ring-black ring-opacity-5">
            <x-slot name="trigger">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-brandBlue/30 transition">
                        <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </button>
                @else
                    <button type="button" class="inline-flex items-center gap-2 px-3 py-2 rounded-mdx text-body text-primaryText bg-white border border-gray-200 hover:bg-brandGray/5 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 min-h-[44px]">
                        <span class="truncate max-w-[140px]">{{ Auth::user()->name }}</span>
                        <svg class="size-4 text-secondaryText shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                @endif
            </x-slot>

            <x-slot name="content">
                <div class="block px-4 py-2 text-subtitle text-secondaryText">
                    {{ __('Manage Account') }}
                </div>
                <x-dropdown-link href="{{ route('profile.show') }}">
                    {{ __('Profile') }}
                </x-dropdown-link>
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                        {{ __('API Tokens') }}
                    </x-dropdown-link>
                @endif
                <div class="border-t border-gray-200"></div>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
