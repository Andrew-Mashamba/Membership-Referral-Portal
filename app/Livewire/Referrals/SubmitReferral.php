<?php

namespace App\Livewire\Referrals;

use App\Models\Referral;
use App\Services\ReferralIdService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class SubmitReferral extends Component
{
    public string $referred_name = '';
    public string $referred_phone = '';
    public string $referred_email = '';
    public string $relationship = '';
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'referred_name' => ['required', 'string', 'max:255'],
            'referred_phone' => ['nullable', 'string', 'max:50'],
            'referred_email' => ['nullable', 'email', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'referred_name' => 'referred person name',
            'referred_phone' => 'phone',
            'referred_email' => 'email',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if (empty($this->referred_phone) && empty($this->referred_email)) {
            $this->addError('referred_phone', 'Please provide at least phone or email.');
            return;
        }

        $existing = Referral::where('referrer_id', auth()->id())
            ->where(function ($q) {
                if (trim($this->referred_phone ?? '') !== '') {
                    $q->orWhere('referred_phone', $this->referred_phone);
                }
                if (trim($this->referred_email ?? '') !== '') {
                    $q->orWhere('referred_email', $this->referred_email);
                }
            })->first();
        if ($existing) {
            $this->addError('referred_email', 'You have already submitted a referral for this phone or email.');
            return;
        }

        $referralId = app(ReferralIdService::class)->generate();

        $referral = Referral::create([
            'referral_id' => $referralId,
            'referrer_id' => auth()->id(),
            'referred_name' => $this->referred_name,
            'referred_phone' => $this->referred_phone ?: null,
            'referred_email' => $this->referred_email ?: null,
            'relationship' => $this->relationship ?: null,
            'notes' => $this->notes ?: null,
            'status' => 'pending',
        ]);

        $referral->statusHistories()->create([
            'from_status' => null,
            'to_status' => 'pending',
            'changed_by' => auth()->id(),
            'comment' => 'Submitted',
        ]);

        $this->reset(['referred_name', 'referred_phone', 'referred_email', 'relationship', 'notes']);
        session()->flash('message', 'Referral submitted successfully. ID: ' . $referral->referral_id);
        $this->redirect(route('referrals.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.referrals.submit-referral');
    }
}
