<div class="max-w-5xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
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

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 opacity-0 animate-fade-in-up delay-75">
    <h1 class="text-2xl font-semibold text-primaryText">User management</h1>
    <button type="button" wire:click="openAddUser"
            class="min-h-[48px] px-5 py-3 bg-brandBlue text-white rounded-2xl shadow-soft font-semibold text-body hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
      Add new user
    </button>
  </div>

  @if (session('message'))
    <div class="mb-6 p-4 rounded-xl bg-brandBlue/10 border border-brandBlue/20 text-body text-primaryText opacity-0 animate-fade-in-up delay-75">
      {{ session('message') }}
    </div>
  @endif
  @if (session('error'))
    <div class="mb-6 p-4 rounded-xl bg-brandGray/15 border border-brandGray/20 text-body text-secondaryText opacity-0 animate-fade-in-up delay-75">
      {{ session('error') }}
    </div>
  @endif

  {{-- Filters --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 p-5 sm:p-6 mb-6 opacity-0 animate-fade-in-up delay-150 transition-all duration-300 hover:shadow-card">
    <div class="flex flex-wrap gap-4 sm:gap-5 items-end">
      <div class="flex-1 min-w-[200px]">
        <label for="users-search" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Search</label>
        <input id="users-search" type="search" wire:model.live.debounce.300ms="search"
               placeholder="Name, email, membership..."
               class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
      </div>
      <div class="min-w-[160px]">
        <label for="users-role" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Role</label>
        <select id="users-role" wire:model.live="roleFilter"
                class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
          <option value="">All roles</option>
          <option value="member">Member</option>
          <option value="approver">Approver</option>
          <option value="administrator">Administrator</option>
        </select>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden opacity-0 animate-fade-in-up delay-225 transition-all duration-300 hover:shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full text-body text-primaryText" role="table">
        <thead>
          <tr class="border-b border-brandGray/20 bg-brandGray/5">
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Name</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Email</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Membership #</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Role</th>
            <th class="text-left py-3 px-4 text-subtitle font-semibold text-secondaryText uppercase tracking-wider" scope="col">Access</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr class="border-b border-brandGray/20 last:border-b-0 hover:bg-brandGray/5 transition-colors duration-200">
              <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
              <td class="py-3 px-4 text-secondaryText">{{ $user->email }}</td>
              <td class="py-3 px-4">{{ $user->membership_number ?? '–' }}</td>
              <td class="py-3 px-4">
                @if ($user->id !== auth()->id())
                  <select class="rounded-xl border border-brandGray/20 px-3 py-2 text-body text-primaryText bg-primaryBg min-h-[40px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                          @change="$wire.updateRole({{ $user->id }}, $event.target.value)">
                    <option value="member" @selected($user->role === 'member')>Member</option>
                    <option value="approver" @selected($user->role === 'approver')>Approver</option>
                    <option value="administrator" @selected($user->role === 'administrator')>Administrator</option>
                  </select>
                @else
                  <span class="text-secondaryText">{{ ucfirst($user->role) }} (you)</span>
                @endif
              </td>
              <td class="py-3 px-4">
                @if ($user->id !== auth()->id())
                  <button type="button"
                          wire:click="toggleActive({{ $user->id }})"
                          class="min-h-[44px] px-3 py-2 rounded-xl text-body font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-1 {{ $user->is_active ? 'bg-brandBlue/10 text-brandBlue border border-brandBlue/20 hover:bg-brandBlue/15' : 'bg-brandGray/15 text-secondaryText border border-brandGray/20 hover:bg-brandGray/20' }}">
                    {{ $user->is_active ? 'Enabled' : 'Disabled' }}
                  </button>
                @else
                  <span class="text-secondaryText">–</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-12 px-4 text-center text-body text-secondaryText">No users found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($users->hasPages())
      <div class="border-t border-brandGray/20 px-4 py-3 flex justify-center">
        {{ $users->links() }}
      </div>
    @endif
  </div>

  {{-- Add user modal --}}
  @if ($showAddModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primaryText/50" wire:click.self="closeAddUser" role="dialog" aria-modal="true" aria-labelledby="add-user-modal-title">
      <div class="bg-white rounded-2xl shadow-card border border-white/80 p-5 sm:p-6 max-w-md w-full">
        <h2 id="add-user-modal-title" class="text-title font-semibold text-primaryText mb-4">Add new user</h2>
        <p class="text-body text-secondaryText mb-4">Credentials and a set-password link will be sent to their email.</p>

        <form wire:submit="createUser" class="space-y-4">
          <div>
            <label for="new-name" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Name <span class="text-primaryText">*</span></label>
            <input id="new-name" type="text" wire:model="newName"
                   class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                   required>
            @error('newName')
              <p class="text-subtitle text-secondaryText mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="new-email" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Email <span class="text-primaryText">*</span></label>
            <input id="new-email" type="email" wire:model="newEmail"
                   class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                   required>
            @error('newEmail')
              <p class="text-subtitle text-secondaryText mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="new-role" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Role <span class="text-primaryText">*</span></label>
            <select id="new-role" wire:model="newRole"
                    class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none">
              <option value="member">Member</option>
              <option value="approver">Approver</option>
              <option value="administrator">Administrator</option>
            </select>
          </div>
          <div>
            <label for="new-membership" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Membership number</label>
            <input id="new-membership" type="text" wire:model="newMembershipNumber"
                   class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                   placeholder="Optional">
          </div>
          <div>
            <label for="new-phone" class="block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5">Phone</label>
            <input id="new-phone" type="text" wire:model="newPhone"
                   class="w-full rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg min-h-[48px] focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none"
                   placeholder="Optional">
          </div>

          <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="flex-1 min-h-[48px] px-4 py-3 bg-brandBlue text-white rounded-2xl font-semibold text-body focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
              Create and send email
            </button>
            <button type="button" wire:click="closeAddUser"
                    class="flex-1 min-h-[48px] px-4 py-3 bg-primaryBg border border-brandGray/20 text-primaryText rounded-2xl font-semibold text-body hover:bg-brandGray/5 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  @endif
</div>
