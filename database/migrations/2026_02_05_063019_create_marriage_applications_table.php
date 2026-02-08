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
        Schema::create('marriage_applications', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->unique()->index();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'issued'])->default('pending');
            $table->string('foreigner_type')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            
            // Track which staff handled the application
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriage_applications');
    }
};
