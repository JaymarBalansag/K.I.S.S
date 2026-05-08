<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manual_marriage_license_applications', function (Blueprint $table) {
            $table->string('groom_id_issued_at')->nullable()->after('groom_id_number');
            $table->date('groom_id_issued_on')->nullable()->after('groom_id_issued_at');

            $table->string('bride_id_issued_at')->nullable()->after('bride_id_number');
            $table->date('bride_id_issued_on')->nullable()->after('bride_id_issued_at');
        });
    }

    public function down(): void
    {
        Schema::table('manual_marriage_license_applications', function (Blueprint $table) {
            $table->dropColumn([
                'groom_id_issued_at',
                'groom_id_issued_on',
                'bride_id_issued_at',
                'bride_id_issued_on',
            ]);
        });
    }
};

