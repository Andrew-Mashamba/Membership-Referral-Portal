<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_id')->unique(); // e.g. REF-20260210-0001
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->string('referred_name');
            $table->string('referred_phone')->nullable();
            $table->string('referred_email')->nullable();
            $table->string('relationship')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, in_review, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['referrer_id', 'status']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
