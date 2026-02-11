<?php

namespace Database\Seeders;

use App\Models\Referral;
use App\Models\ReferralStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReferralSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('email', 'member@example.com')->first();
        $approver = User::where('email', 'approver@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();

        if (! $member || ! $approver) {
            return;
        }

        $referrals = [
            [
                'referrer' => $member,
                'referred_name' => 'Jane Mwangi',
                'referred_phone' => '+255712111001',
                'referred_email' => 'jane.mwangi@example.com',
                'relationship' => 'Colleague',
                'notes' => 'Interested in joining SACCOS.',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'John Kamau',
                'referred_phone' => '+255712111002',
                'referred_email' => null,
                'relationship' => 'Friend',
                'notes' => null,
                'status' => 'approved',
                'approved_by' => $approver,
                'approved_at' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Mary Otieno',
                'referred_phone' => null,
                'referred_email' => 'mary.otieno@example.com',
                'relationship' => 'Neighbour',
                'notes' => 'Referred for membership.',
                'status' => 'rejected',
                'rejection_reason' => 'Incomplete documentation provided.',
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Peter Ochieng',
                'referred_phone' => '+255712111004',
                'referred_email' => 'peter.o@example.com',
                'relationship' => 'Relative',
                'notes' => null,
                'status' => 'in_review',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Grace Wanjiku',
                'referred_phone' => '+255712111005',
                'referred_email' => null,
                'relationship' => 'Friend',
                'notes' => 'Long-time acquaintance.',
                'status' => 'approved',
                'approved_by' => $admin,
                'approved_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'referrer' => $approver,
                'referred_name' => 'Daniel Kimani',
                'referred_phone' => '+255712111006',
                'referred_email' => 'daniel.k@example.com',
                'relationship' => 'Colleague',
                'notes' => null,
                'status' => 'approved',
                'approved_by' => $admin,
                'approved_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(12),
            ],
            // Extra sample referrals for member (so /referrals list is well populated)
            [
                'referrer' => $member,
                'referred_name' => 'Joseph Mushi',
                'referred_phone' => '+255754111020',
                'referred_email' => 'j.mushi@example.com',
                'relationship' => 'Colleague',
                'notes' => 'Wants to join next quarter.',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Sarah Hassan',
                'referred_phone' => '+255712111021',
                'referred_email' => null,
                'relationship' => 'Friend',
                'notes' => null,
                'status' => 'approved',
                'approved_by' => $approver,
                'approved_at' => Carbon::now()->subDays(6),
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Michael Mwita',
                'referred_phone' => null,
                'referred_email' => 'm.mwita@example.com',
                'relationship' => 'Relative',
                'notes' => 'Cousin interested in SACCOS.',
                'status' => 'in_review',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Amina Juma',
                'referred_phone' => '+255712111023',
                'referred_email' => 'amina.j@example.com',
                'relationship' => 'Neighbour',
                'notes' => null,
                'status' => 'rejected',
                'rejection_reason' => 'Duplicate application.',
                'created_at' => Carbon::now()->subDays(14),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Charles Kipanga',
                'referred_phone' => '+255712111024',
                'referred_email' => null,
                'relationship' => 'Friend',
                'notes' => 'Referred from church group.',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Neema Lyimo',
                'referred_phone' => '+255754111025',
                'referred_email' => 'neema.l@example.com',
                'relationship' => 'Colleague',
                'notes' => null,
                'status' => 'approved',
                'approved_by' => $admin,
                'approved_at' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'David Shayo',
                'referred_phone' => '+255712111026',
                'referred_email' => null,
                'relationship' => 'Relative',
                'notes' => 'Brother-in-law.',
                'status' => 'in_review',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'referrer' => $member,
                'referred_name' => 'Fatuma Bakari',
                'referred_phone' => null,
                'referred_email' => 'fatuma.b@example.com',
                'relationship' => 'Friend',
                'notes' => null,
                'status' => 'approved',
                'approved_by' => $approver,
                'approved_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(25),
            ],
        ];

        foreach ($referrals as $i => $data) {
            $referrer = $data['referrer'];
            $approvedBy = $data['approved_by'] ?? null;
            $approvedAt = $data['approved_at'] ?? null;
            $rejectionReason = $data['rejection_reason'] ?? null;
            $createdAt = $data['created_at'];
            unset($data['referrer'], $data['approved_by'], $data['approved_at'], $data['rejection_reason'], $data['created_at']);

            $referralId = 'REF-' . $createdAt->format('Ymd') . '-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT);

            $referral = Referral::firstOrCreate(
                ['referral_id' => $referralId],
                [
                'referral_id' => $referralId,
                'referrer_id' => $referrer->id,
                'referred_name' => $data['referred_name'],
                'referred_phone' => $data['referred_phone'],
                'referred_email' => $data['referred_email'],
                'relationship' => $data['relationship'],
                'notes' => $data['notes'],
                'status' => $data['status'],
                'rejection_reason' => $rejectionReason,
                'approved_by' => $approvedBy?->id,
                'approved_at' => $approvedAt,
                'created_at' => $createdAt,
            ]
            );

            if ($referral->wasRecentlyCreated) {
                ReferralStatusHistory::create([
                'referral_id' => $referral->id,
                'from_status' => null,
                'to_status' => 'pending',
                'changed_by' => $referrer->id,
                'comment' => 'Submitted',
                'created_at' => $createdAt,
            ]);

            if ($data['status'] === 'approved') {
                ReferralStatusHistory::create([
                    'referral_id' => $referral->id,
                    'from_status' => 'pending',
                    'to_status' => 'approved',
                    'changed_by' => $approvedBy->id,
                    'comment' => 'Approved',
                    'created_at' => $approvedAt,
                ]);
            } elseif ($data['status'] === 'rejected') {
                ReferralStatusHistory::create([
                    'referral_id' => $referral->id,
                    'from_status' => 'pending',
                    'to_status' => 'rejected',
                    'changed_by' => $approver->id,
                    'comment' => $rejectionReason,
                    'created_at' => (clone $createdAt)->addHours(2),
                ]);
            } elseif ($data['status'] === 'in_review') {
                ReferralStatusHistory::create([
                    'referral_id' => $referral->id,
                    'from_status' => 'pending',
                    'to_status' => 'in_review',
                    'changed_by' => $approver->id,
                    'comment' => 'Under review',
                    'created_at' => (clone $createdAt)->addHours(1),
                ]);
            }
            }
        }
    }
}
