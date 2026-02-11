<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('identifier'); // email or membership_number used
            $table->string('ip_address', 45)->nullable();
            $table->boolean('success');
            $table->timestamp('attempted_at');
        });

        Schema::table('login_attempts', function (Blueprint $table) {
            $table->index(['identifier', 'attempted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
