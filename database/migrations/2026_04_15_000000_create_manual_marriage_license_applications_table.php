<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_marriage_license_applications', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->unique()->index();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('phone_number')->nullable();

            $table->string('groom_first_name');
            $table->string('groom_middle_name')->nullable();
            $table->string('groom_last_name');
            $table->date('groom_birth_date');
            $table->unsignedInteger('groom_age');
            $table->string('groom_birth_city');
            $table->string('groom_birth_province');
            $table->string('groom_birth_country');
            $table->string('groom_sex');
            $table->string('groom_citizenship');
            $table->string('groom_religion');
            $table->string('groom_civil_status');
            $table->text('groom_residence_address');
            $table->text('groom_dissolution_details')->nullable();
            $table->string('groom_dissolution_place')->nullable();
            $table->date('groom_dissolution_date')->nullable();
            $table->string('groom_relationship_degree')->nullable();
            $table->string('groom_father_first_name');
            $table->string('groom_father_middle_name')->nullable();
            $table->string('groom_father_last_name');
            $table->string('groom_father_citizenship');
            $table->text('groom_father_residence');
            $table->string('groom_mother_first_name');
            $table->string('groom_mother_middle_name')->nullable();
            $table->string('groom_mother_last_name');
            $table->string('groom_mother_citizenship');
            $table->text('groom_mother_residence');
            $table->string('groom_id_type')->nullable();
            $table->string('groom_id_number')->nullable();

            $table->string('bride_first_name');
            $table->string('bride_middle_name')->nullable();
            $table->string('bride_last_name');
            $table->date('bride_birth_date');
            $table->unsignedInteger('bride_age');
            $table->string('bride_birth_city');
            $table->string('bride_birth_province');
            $table->string('bride_birth_country');
            $table->string('bride_sex');
            $table->string('bride_citizenship');
            $table->string('bride_religion');
            $table->string('bride_civil_status');
            $table->text('bride_residence_address');
            $table->text('bride_dissolution_details')->nullable();
            $table->string('bride_dissolution_place')->nullable();
            $table->date('bride_dissolution_date')->nullable();
            $table->string('bride_relationship_degree')->nullable();
            $table->string('bride_father_first_name');
            $table->string('bride_father_middle_name')->nullable();
            $table->string('bride_father_last_name');
            $table->string('bride_father_citizenship');
            $table->text('bride_father_residence');
            $table->string('bride_mother_first_name');
            $table->string('bride_mother_middle_name')->nullable();
            $table->string('bride_mother_last_name');
            $table->string('bride_mother_citizenship');
            $table->text('bride_mother_residence');
            $table->string('bride_id_type')->nullable();
            $table->string('bride_id_number')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_marriage_license_applications');
    }
};
