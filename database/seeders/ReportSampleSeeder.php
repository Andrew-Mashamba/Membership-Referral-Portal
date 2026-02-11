<?php

namespace Database\Seeders;

use App\Models\Referral;
use App\Models\ReferralStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReportSampleSeeder extends Seeder
{
    /**
     * Seed referrals with created_at in the past (relative to "now")
     * so /admin/reports always shows data for the default date range (start of month → today).
     * Uses updateOrCreate so re-running updates dates to be relative to current day.
     */
    public function run(): void
    {
        $member = User::where('email', 'member@example.com')->first();
        $approver = User::where('email', 'approver@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();

        if (! $member || ! $approver) {
            return;
        }

        // Remove old report-sample referrals (old ID format or stale dates) so this run leaves a clean set
        Referral::where('referral_id', 'like', 'REF-RPT-%')->delete();

        $now = Carbon::now();
        // All dates in the past: 1, 3, 5, 7, 10, 12, 15, 20 days ago (current month) + 35, 45, 55 days ago (previous months)
        $samples = [
            ['daysAgo' => 1, 'name' => 'Alice Mbeki', 'status' => 'approved', 'approved_by' => $approver],
            ['daysAgo' => 3, 'name' => 'Benard Nkosi', 'status' => 'pending', 'approved_by' => null],
            ['daysAgo' => 5, 'name' => 'Catherine Dube', 'status' => 'in_review', 'approved_by' => null],
            ['daysAgo' => 7, 'name' => 'Dennis Phiri', 'status' => 'rejected', 'approved_by' => null],
            ['daysAgo' => 10, 'name' => 'Eunice Banda', 'status' => 'approved', 'approved_by' => $admin],
            ['daysAgo' => 12, 'name' => 'Frank Mwale', 'status' => 'pending', 'approved_by' => null],
            ['daysAgo' => 15, 'name' => 'Getrude Simkoko', 'status' => 'approved', 'approved_by' => $approver],
            ['daysAgo' => 20, 'name' => 'Henry Chilufya', 'status' => 'pending', 'approved_by' => null],
            ['daysAgo' => 35, 'name' => 'Ivy Tembo', 'status' => 'approved', 'approved_by' => $approver],
            ['daysAgo' => 40, 'name' => 'James Zulu', 'status' => 'rejected', 'approved_by' => null],
            ['daysAgo' => 45, 'name' => 'Kunda Mwansa', 'status' => 'approved', 'approved_by' => $admin],
            ['daysAgo' => 50, 'name' => 'Linda Kasonde', 'status' => 'approved', 'approved_by' => $approver],
            ['daysAgo' => 55, 'name' => 'Moses Chisanga', 'status' => 'pending', 'approved_by' => null],
            ['daysAgo' => 60, 'name' => 'Nancy Mulenga', 'status' => 'approved', 'approved_by' => $approver],
            ['daysAgo' => 65, 'name' => 'Oscar Mwila', 'status' => 'rejected', 'approved_by' => null],
            ['daysAgo' => 70, 'name' => 'Patricia Ngoma', 'status' => 'approved', 'approved_by' => $admin],
        ];

        foreach ($samples as $i => $sample) {
            $date = $now->copy()->subDays($sample['daysAgo']);
            $referralId = 'REF-RPT-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);

            $referral = Referral::updateOrCreate(
                ['referral_id' => $referralId],
                [
                    'referrer_id' => $member->id,
                    'referred_name' => $sample['name'],
                    'referred_phone' => '+2557' . str_pad((string) (111000000 + $i), 9, '0', STR_PAD_LEFT),
                    'referred_email' => strtolower(str_replace(' ', '.', $sample['name'])) . '@example.com',
                    'relationship' => ['Colleague', 'Friend', 'Relative'][$i % 3],
                    'notes' => null,
                    'status' => $sample['status'],
                    'rejection_reason' => $sample['status'] === 'rejected' ? 'Sample rejection reason.' : null,
                    'approved_by' => $sample['approved_by']?->id,
                    'approved_at' => $sample['status'] === 'approved' ? $date->copy()->addHours(2) : null,
                    'created_at' => $date,
                ]
            );

            if ($referral->wasRecentlyCreated) {
                ReferralStatusHistory::create([
                    'referral_id' => $referral->id,
                    'from_status' => null,
                    'to_status' => 'pending',
                    'changed_by' => $member->id,
                    'comment' => 'Submitted',
                    'created_at' => $date,
                ]);
                if ($sample['status'] === 'approved' && $sample['approved_by']) {
                    ReferralStatusHistory::create([
                        'referral_id' => $referral->id,
                        'from_status' => 'pending',
                        'to_status' => 'approved',
                        'changed_by' => $sample['approved_by']->id,
                        'comment' => 'Approved',
                        'created_at' => $date->copy()->addHours(2),
                    ]);
                }
                if ($sample['status'] === 'rejected') {
                    ReferralStatusHistory::create([
                        'referral_id' => $referral->id,
                        'from_status' => 'pending',
                        'to_status' => 'rejected',
                        'changed_by' => $approver->id,
                        'comment' => 'Sample rejection reason.',
                        'created_at' => $date->copy()->addHours(1),
                    ]);
                }
            } else {
                // Re-run: ensure created_at is updated so report range includes these
                $referral->update(['created_at' => $date]);
            }
        }
    }
}
