<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manual_marriage_license_applications', function (Blueprint $table) {
            $table->string('groom_consent_name')->nullable()->after('groom_mother_residence');
            $table->string('groom_consent_relationship')->nullable()->after('groom_consent_name');
            $table->string('groom_consent_citizenship')->nullable()->after('groom_consent_relationship');
            $table->text('groom_consent_residence')->nullable()->after('groom_consent_citizenship');

            $table->string('bride_consent_name')->nullable()->after('bride_mother_residence');
            $table->string('bride_consent_relationship')->nullable()->after('bride_consent_name');
            $table->string('bride_consent_citizenship')->nullable()->after('bride_consent_relationship');
            $table->text('bride_consent_residence')->nullable()->after('bride_consent_citizenship');
        });
    }

    public function down(): void
    {
        Schema::table('manual_marriage_license_applications', function (Blueprint $table) {
            $table->dropColumn([
                'groom_consent_name',
                'groom_consent_relationship',
                'groom_consent_citizenship',
                'groom_consent_residence',
                'bride_consent_name',
                'bride_consent_relationship',
                'bride_consent_citizenship',
                'bride_consent_residence',
            ]);
        });
    }
};
