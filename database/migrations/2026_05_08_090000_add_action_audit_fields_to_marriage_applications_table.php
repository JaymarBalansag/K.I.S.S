<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marriage_applications', function (Blueprint $table) {
            $table->foreignId('approved_by_id')->nullable()->after('confirmed_at')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by_id');

            $table->foreignId('rejected_by_id')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable()->after('rejected_by_id');

            $table->foreignId('issued_by_id')->nullable()->after('rejected_at')->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable()->after('issued_by_id');
        });
    }

    public function down(): void
    {
        Schema::table('marriage_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by_id');
            $table->dropColumn('approved_at');

            $table->dropConstrainedForeignId('rejected_by_id');
            $table->dropColumn('rejected_at');

            $table->dropConstrainedForeignId('issued_by_id');
            $table->dropColumn('issued_at');
        });
    }
};

