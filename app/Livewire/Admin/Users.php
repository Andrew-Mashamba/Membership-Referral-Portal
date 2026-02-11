<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\NewUserCredentialsNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class Users extends Component
{
    use WithPagination;

    public string $roleFilter = '';
    public string $search = '';

    public bool $showAddModal = false;
    public string $newName = '';
    public string $newEmail = '';
    public string $newRole = 'member';
    public string $newMembershipNumber = '';
    public string $newPhone = '';

    public function openAddUser(): void
    {
        $this->reset(['newName', 'newEmail', 'newRole', 'newMembershipNumber', 'newPhone']);
        $this->newRole = 'member';
        $this->showAddModal = true;
    }

    public function closeAddUser(): void
    {
        $this->showAddModal = false;
        $this->resetValidation();
    }

    public function createUser(): void
    {
        $this->validate([
            'newName' => ['required', 'string', 'max:255'],
            'newEmail' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'newRole' => ['required', 'in:member,approver,administrator'],
            'newMembershipNumber' => ['nullable', 'string', 'max:50'],
            'newPhone' => ['nullable', 'string', 'max:50'],
        ], [], [
            'newName' => 'name',
            'newEmail' => 'email',
            'newRole' => 'role',
            'newMembershipNumber' => 'membership number',
            'newPhone' => 'phone',
        ]);

        $tempPassword = Str::password(12);
        $user = User::create([
            'name' => $this->newName,
            'email' => strtolower($this->newEmail),
            'password' => Hash::make($tempPassword),
            'role' => $this->newRole,
            'membership_number' => $this->newMembershipNumber ?: null,
            'phone' => $this->newPhone ?: null,
            'is_active' => true,
        ]);

        $token = Password::broker()->createToken($user);
        $setPasswordUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email]));

        $user->notify(new NewUserCredentialsNotification($tempPassword, $setPasswordUrl));

        $this->closeAddUser();
        session()->flash('message', 'User created. Credentials and set-password link have been sent to ' . $user->email);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updateRole(int $userId, string $role): void
    {
        if (! in_array($role, ['member', 'approver', 'administrator'], true)) {
            return;
        }
        $user = User::findOrFail($userId);
        $user->update(['role' => $role]);
        session()->flash('message', 'Role updated for ' . $user->name);
    }

    public function toggleActive(int $userId): void
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot disable your own account.');
            return;
        }
        $user->update(['is_active' => ! $user->is_active]);
        session()->flash('message', ($user->is_active ? 'Enabled' : 'Disabled') . ' access for ' . $user->name);
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('membership_number', 'like', '%' . $this->search . '%');
            }))
            ->when($this->roleFilter !== '', fn ($q) => $q->where('role', $this->roleFilter))
            ->orderBy('name');

        $users = $query->paginate(15);

        return view('livewire.admin.users', ['users' => $users]);
    }
}
