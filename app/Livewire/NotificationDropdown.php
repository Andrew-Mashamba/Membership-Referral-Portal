<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationDropdown extends Component
{
    public function markAsRead(string $id): void
    {
        auth()->user()->unreadNotifications()->where('id', $id)->update(['read_at' => now()]);
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.notification-dropdown', [
            'notifications' => auth()->user()->unreadNotifications()->take(10)->get(),
            'unreadCount' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
