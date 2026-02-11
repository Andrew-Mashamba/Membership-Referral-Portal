<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportReferralsController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');

        $query = Referral::with('referrer', 'approver')
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->orderBy('created_at');

        $filename = 'referrals-' . ($dateFrom ?? 'all') . '-to-' . ($dateTo ?? 'all') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Referral ID', 'Referred Name', 'Phone', 'Email', 'Status', 'Referrer', 'Submitted', 'Approved By']);
            foreach ($query->cursor() as $r) {
                fputcsv($handle, [
                    $r->referral_id,
                    $r->referred_name,
                    $r->referred_phone ?? '',
                    $r->referred_email ?? '',
                    $r->status,
                    $r->referrer->name ?? '',
                    $r->created_at->format('Y-m-d H:i'),
                    $r->approver->name ?? '',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
