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
            $table->dropForeign(['application_id']);
            $table->dropColumn([
                'application_id',
                'sent_from',
                'source',
                'external_id',
                'error_message',
                'sent_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_requests', function (Blueprint $table) {
            $table->foreignId('application_id')
                ->nullable()
                ->after('id')
                ->constrained('marriage_applications')
                ->nullOnDelete();
            $table->enum('sent_from', ['kiss', 'mads'])->default('kiss')->after('status');
            $table->string('source', 50)->nullable()->after('sent_from');
            $table->string('external_id', 100)->nullable()->after('source');
            $table->text('error_message')->nullable()->after('external_id');
            $table->timestamp('sent_at')->nullable()->after('error_message');

            $table->index('sent_from');
            $table->index(['source', 'external_id']);
        });
    }
};
