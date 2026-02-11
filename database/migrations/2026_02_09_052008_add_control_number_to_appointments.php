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
        Schema::table('appointments', function (Blueprint $table) {
            // Add the control number column after the phone number
            $table->string('control_number')->unique()->after('phone_number');

            // Update the enum to PMOC or Civil Wedding
            // Note: use ->change() to modify existing column
            $table->enum('appointment_type', ['PMOC', 'Civil Wedding'])->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Remove the control number column
            $table->dropColumn('control_number');

            // Revert the enum changes
            $table->enum('appointment_type', ['PMOC', 'Civil Wedding', 'Other'])->change();
        });
    }
};
