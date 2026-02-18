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
            $table->string('parental_requirement')->nullable()->after('mother_residence');
            $table->string('source_first_name')->nullable()->after('parental_requirement');
            $table->string('source_middle_name')->nullable()->after('source_first_name');
            $table->string('source_last_name')->nullable()->after('source_middle_name');
            $table->string('source_citizenship')->nullable()->after('source_last_name');
            $table->string('source_relationship')->nullable()->after('source_citizenship');
            $table->text('source_residence')->nullable()->after('source_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'parental_requirement',
                'source_first_name',
                'source_middle_name',
                'source_last_name',
                'source_citizenship',
                'source_relationship',
                'source_residence',
            ]);
        });
    }
};

