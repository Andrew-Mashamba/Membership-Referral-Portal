<?php

namespace Database\Seeders;

use App\Models\Referral;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('email', 'member@example.com')->first();
        $approver = User::where('email', 'approver@example.com')->first();

        if (! $member) {
            return;
        }

        // Member's referrals (approved/rejected)
        $memberReferrals = Referral::where('referrer_id', $member->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderByDesc('approved_at')
            ->orderByDesc('updated_at')
            ->get();

        foreach ($memberReferrals as $referral) {
            if ($this->notificationExists($member, $referral->referral_id)) {
                continue;
            }

            $message = $referral->isApproved()
                ? "Referral {$referral->referral_id} for {$referral->referred_name} was approved."
                : "Referral {$referral->referral_id} for {$referral->referred_name} was not approved.";

            $member->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'type' => \App\Notifications\ReferralStatusNotification::class,
                'data' => [
                    'referral_id' => $referral->referral_id,
                    'referred_name' => $referral->referred_name,
                    'status' => $referral->status,
                    'message' => $message,
                ],
                'read_at' => $referral->created_at && $referral->created_at->diffInDays(now()) > 2 ? now()->subDays(1) : null,
                'created_at' => $referral->approved_at ?? $referral->updated_at,
            ]);
        }

        // Approver's referrals (approved/rejected) - notify approver when their referral is acted on
        if ($approver) {
            $approverReferrals = Referral::where('referrer_id', $approver->id)
                ->whereIn('status', ['approved', 'rejected'])
                ->orderByDesc('approved_at')
                ->get();

            foreach ($approverReferrals as $referral) {
                if ($this->notificationExists($approver, $referral->referral_id)) {
                    continue;
                }

                $message = $referral->isApproved()
                    ? "Referral {$referral->referral_id} for {$referral->referred_name} was approved."
                    : "Referral {$referral->referral_id} for {$referral->referred_name} was not approved.";

                $approver->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => \App\Notifications\ReferralStatusNotification::class,
                    'data' => [
                        'referral_id' => $referral->referral_id,
                        'referred_name' => $referral->referred_name,
                        'status' => $referral->status,
                        'message' => $message,
                    ],
                    'read_at' => null,
                    'created_at' => $referral->approved_at ?? $referral->updated_at,
                ]);
            }
        }
    }

    private function notificationExists(User $user, string $referralId): bool
    {
        return $user->notifications()
            ->where('type', \App\Notifications\ReferralStatusNotification::class)
            ->where('data->referral_id', $referralId)
            ->exists();
    }
}
