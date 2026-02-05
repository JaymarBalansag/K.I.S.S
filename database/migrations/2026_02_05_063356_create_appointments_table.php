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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('marriage_applications', "id")->onDelete('cascade');
            $table->enum('appointment_type', ['counseling', 'ceremony']);

            $table->string('first_name'); 
            $table->string('last_name'); 
            $table->string('middle_name')->nullable(); 
            $table->string('extension')->nullable(); 
            $table->string('phone_number'); 
            $table->string('email')->nullable(); 
            
            $table->dateTime('requested_date'); // What the user picked
            $table->dateTime('scheduled_date')->nullable(); // What staff confirmed
            
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
