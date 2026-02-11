<div x-data="{ open: false }" class="relative">
    <button @click="open = ! open" type="button" class="p-2 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-brandBlue" aria-label="Notifications">
        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-brandBlue text-[10px] text-white">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>
    <div x-show="open" @click.outside="open = false" x-cloak
         class="absolute left-0 mt-2 w-72 rounded-lg bg-white shadow-card border border-gray-200 py-2 z-50 max-h-80 overflow-y-auto">
        <div class="px-4 py-2 text-sm font-semibold text-primaryText border-b flex justify-between items-center">
            <span>Notifications</span>
            @if($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead" class="text-subtitle text-brandBlue hover:underline">Mark all read</button>
            @endif
        </div>
        @forelse ($notifications as $n)
            @php
                $data = $n->data;
                $referral = isset($data['referral_id']) ? \App\Models\Referral::where('referral_id', $data['referral_id'])->first() : null;
            @endphp
            <a href="{{ $referral ? route('referrals.show', $referral) : '#' }}"
               wire:click="markAsRead('{{ $n->id }}')"
               class="block px-4 py-2 text-body text-primaryText hover:bg-gray-50 border-b border-gray-100">
                <span class="line-clamp-2">{{ $data['message'] ?? 'Referral update' }}</span>
                <span class="text-subtitle text-secondaryText">{{ $n->created_at->diffForHumans() }}</span>
            </a>
        @empty
            <div class="px-4 py-4 text-body text-secondaryText">No new notifications.</div>
        @endforelse
    </div>
</div>
