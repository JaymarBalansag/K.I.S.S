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
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('government_id_type')->nullable()->after('source_residence');
            $table->string('government_id_number')->nullable()->after('government_id_type');
            $table->string('government_id_issued_at')->nullable()->after('government_id_number');
            $table->date('government_id_issued_on')->nullable()->after('government_id_issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'government_id_type',
                'government_id_number',
                'government_id_issued_at',
                'government_id_issued_on',
            ]);
        });
    }
};

