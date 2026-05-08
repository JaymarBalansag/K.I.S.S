<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('confirmed_by_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by_id');

            $table->foreignId('cancelled_by_id')->nullable()->after('confirmed_at')->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_by_id');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('confirmed_by_id');
            $table->dropColumn('confirmed_at');

            $table->dropConstrainedForeignId('cancelled_by_id');
            $table->dropColumn('cancelled_at');
        });
    }
};

