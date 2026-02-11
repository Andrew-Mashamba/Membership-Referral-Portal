<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class Settings extends Component
{
    public string $referral_id_prefix = 'REF';
    public string $lockout_attempts = '5';
    public string $lockout_minutes = '15';
    public string $session_timeout_minutes = '120';
    public bool $email_notifications_enabled = true;
    public bool $sms_notifications_enabled = false;

    public function mount(): void
    {
        $this->referral_id_prefix = Setting::get('referral_id_prefix', 'REF');
        $this->lockout_attempts = (string) (Setting::get('lockout_attempts') ?: 5);
        $this->lockout_minutes = (string) (Setting::get('lockout_minutes') ?: 15);
        $this->session_timeout_minutes = (string) (Setting::get('session_timeout_minutes') ?: 120);
        $this->email_notifications_enabled = (bool) (Setting::get('email_notifications_enabled') !== '0');
        $this->sms_notifications_enabled = (bool) (Setting::get('sms_notifications_enabled') === '1');
    }

    protected function rules(): array
    {
        return [
            'referral_id_prefix' => 'required|string|max:20',
            'lockout_attempts' => 'required|integer|min:1|max:20',
            'lockout_minutes' => 'required|integer|min:1|max:1440',
            'session_timeout_minutes' => 'required|integer|min:5|max:1440',
            'email_notifications_enabled' => 'boolean',
            'sms_notifications_enabled' => 'boolean',
        ];
    }

    public function save(): void
    {
        $this->validate();

        Setting::set('referral_id_prefix', $this->referral_id_prefix);
        Setting::set('lockout_attempts', $this->lockout_attempts);
        Setting::set('lockout_minutes', $this->lockout_minutes);
        Setting::set('session_timeout_minutes', $this->session_timeout_minutes);
        Setting::set('email_notifications_enabled', $this->email_notifications_enabled ? '1' : '0');
        Setting::set('sms_notifications_enabled', $this->sms_notifications_enabled ? '1' : '0');

        session()->flash('message', 'Settings saved.');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
