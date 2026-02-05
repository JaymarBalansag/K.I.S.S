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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained("marriage_applications", "id")->onDelete('cascade');
            $table->enum('applicant_type', ['groom', 'bride']);

            // Name Columns (Step 1 & 14)
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            // Birth Details (Step 2/3 & 15/16)
            $table->date('birth_date'); // Combined from day/month/year
            $table->string('birth_city');
            $table->string('birth_province');
            $table->string('birth_country');

            // Personal Info (Step 4/6/7 & 17/19/20)
            $table->integer('age');
            $table->string('sex');
            $table->string('citizenship');
            $table->string('religion');
            $table->string('civil_status');
            $table->text('residence_address'); // Step 5 & 18

            // Dissolution Info (If not Single)
            $table->text('dissolution_details')->nullable(); 
            $table->string('dissolution_place')->nullable();
            $table->date('dissolution_date')->nullable();

            // Relationship Degree (Step 8 & 21)
            $table->string('relationship_degree')->nullable();

            // Father's Info (Step 9/10 & 22/23)
            $table->string('father_first_name');
            $table->string('father_middle_name')->nullable();
            $table->string('father_last_name');
            $table->string('father_citizenship');
            $table->text('father_residence');

            // Mother's Info (Step 11/12 & 24/25)
            $table->string('mother_first_name');
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_last_name');
            $table->string('mother_citizenship');
            $table->text('mother_residence');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
