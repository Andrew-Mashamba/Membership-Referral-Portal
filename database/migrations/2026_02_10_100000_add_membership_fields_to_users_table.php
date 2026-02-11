<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('membership_number')->nullable()->unique()->after('email');
            $table->string('phone')->nullable()->after('membership_number');
            $table->string('role')->default('member')->after('phone'); // member, approver, administrator
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['membership_number', 'phone', 'role']);
        });
    }
};
