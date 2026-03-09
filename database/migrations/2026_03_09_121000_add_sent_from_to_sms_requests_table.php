<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sms_requests', function (Blueprint $table) {
            $table->enum('sent_from', ['kiss', 'mads'])->default('kiss')->after('status');
            $table->index('sent_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_requests', function (Blueprint $table) {
            $table->dropIndex(['sent_from']);
            $table->dropColumn('sent_from');
        });
    }
};
