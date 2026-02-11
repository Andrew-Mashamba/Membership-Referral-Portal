{{-- Mobile overlay --}}
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-primaryText/50 lg:hidden"
     x-cloak
     style="display: none;"></div>

{{-- Sidebar: full viewport height, DESIGN_GUIDELINES (tokens, borders, focus, touch) --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed top-0 left-0 z-50 w-64 flex flex-col bg-primaryBg shadow-soft border-r 
       border-brandGray/20 h-screen lg:h-full min-h-0 transform transition-transform duration-200 ease-in-out lg:static lg:translate-x-0">
    {{-- Logo --}}


    <div class="shrink-0 flex items-center justify-between h-16 px-4 py-0 -mt-16">
        <a href="{{ route('dashboard') }}" class="block focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2 rounded-mdx">
            <img src="{{ asset('images/atcl-logo.png') }}" alt="ATCL SACCOS" class="h-10 w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
            <span class="text-title font-semibold text-primaryText" style="display:none;">ATCL SACCOS</span>
        </a>
        <button @click="sidebarOpen = false"
                class="lg:hidden p-2 rounded-mdx text-secondaryText hover:bg-brandGray/5 hover:text-primaryText min-h-[48px] min-w-[48px] 
                flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-inset"
                aria-label="Close menu">
            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>






    {{-- Nav links (scrollable, flex-1 min-h-0 so sidebar stays viewport height) --}}
    <nav class="flex-1 min-h-0 overflow-y-auto py-4 px-3 space-y-1">
        <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-sidebar-link>
        <x-sidebar-link href="{{ route('referrals.index') }}" :active="request()->routeIs('referrals.*') && !request()->routeIs('referrals.create')">
            {{ __('My referrals') }}
        </x-sidebar-link>
        <x-sidebar-link href="{{ route('referrals.create') }}" :active="request()->routeIs('referrals.create')">
            {{ __('Submit referral') }}
        </x-sidebar-link>

        @php $role = Auth::user()->role ?? ''; $canAccessAdmin = Auth::user()->isApprover() || in_array($role, ['approver', 'administrator'], true); @endphp
        @if($canAccessAdmin)
            <div class="pt-2 mt-2 border-t border-brandGray/20">
                <p class="px-4 py-2 text-subtitle font-medium text-secondaryText uppercase tracking-wider">{{ __('Admin') }}</p>
            </div>
            <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.referrals.pending') }}" :active="request()->routeIs('admin.referrals.pending')">
                {{ __('Pending referrals') }}
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.referrals.all') }}" :active="request()->routeIs('admin.referrals.all')">
                {{ __('All referrals') }}
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.reports') }}" :active="request()->routeIs('admin.reports')">
                {{ __('Reports') }}
            </x-sidebar-link>
            @if(Auth::user()->isAdmin())
                <x-sidebar-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
                    {{ __('Users') }}
                </x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.settings') }}" :active="request()->routeIs('admin.settings')">
                    {{ __('Settings') }}
                </x-sidebar-link>
            @endif
        @endif
    </nav>

    {{-- Bottom: Notifications + User --}}
    <div class="shrink-0 border-t border-brandGray/20 p-3 space-y-2">
        <div class="flex items-center gap-2 px-4">
            @livewire('notification-dropdown')
            <span class="text-body text-secondaryText">{{ __('Notifications') }}</span>
        </div>
        <div class="px-4 py-2">
            <p class="text-subtitle text-secondaryText truncate">{{ Auth::user()->name }}</p>
            <p class="text-subtitle text-secondaryText truncate">{{ Auth::user()->email }}</p>
        </div>
        <div class="space-y-1">
            <x-sidebar-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                {{ __('Profile') }}
            </x-sidebar-link>
            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                <x-sidebar-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                    {{ __('API Tokens') }}
                </x-sidebar-link>
            @endif
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 min-h-[48px] text-body text-primaryText hover:bg-brandGray/5 hover:text-brandBlue rounded-mdx transition text-left focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-inset">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</aside>
