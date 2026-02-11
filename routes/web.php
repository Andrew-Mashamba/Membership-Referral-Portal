<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PendingReferrals;
use App\Livewire\MemberDashboard;
use App\Livewire\Referrals\ReferralDetail;
use App\Livewire\Referrals\ReferralList;
use App\Livewire\Referrals\SubmitReferral;
use App\Models\Referral;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isApprover()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', ReferralList::class)->name('index');
        Route::get('/create', SubmitReferral::class)->name('create');
        Route::get('/{referral}', ReferralDetail::class)->name('show');
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/referrals/pending', PendingReferrals::class)->name('referrals.pending');
    Route::get('/referrals/all', \App\Livewire\Admin\AllReferrals::class)->name('referrals.all');
    Route::get('/referrals/{referral}', ReferralDetail::class)->name('referrals.show');
    Route::get('/reports', \App\Livewire\Admin\Reports::class)->name('reports');
    Route::get('/reports/export', \App\Http\Controllers\Admin\ExportReferralsController::class)->name('reports.export');
    Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
    Route::get('/users', \App\Livewire\Admin\Users::class)->name('users');
});
