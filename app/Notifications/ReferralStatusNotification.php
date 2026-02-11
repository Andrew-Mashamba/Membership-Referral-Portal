<?php

namespace App\Notifications;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferralStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Referral $referral,
        public string $status
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $refId = $this->referral->referral_id;
        $name = $this->referral->referred_name;
        $isApproved = $this->referral->isApproved();

        $subject = $isApproved
            ? "Referral {$refId} approved"
            : "Referral {$refId} not approved";

        $message = (new MailMessage)
            ->subject($subject)
            ->line($isApproved
                ? "Your referral for {$name} ({$refId}) has been approved."
                : "Your referral for {$name} ({$refId}) was not approved." . ($this->referral->rejection_reason ? ' Reason: ' . $this->referral->rejection_reason : ''));

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'referral_id' => $this->referral->referral_id,
            'referred_name' => $this->referral->referred_name,
            'status' => $this->status,
            'message' => $this->referral->isApproved()
                ? "Referral {$this->referral->referral_id} for {$this->referral->referred_name} was approved."
                : "Referral {$this->referral->referral_id} for {$this->referral->referred_name} was not approved.",
        ];
    }
}
