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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('marriage_applications', "id")->onDelete('cascade');
            $table->enum('owner_type', ['groom', 'bride']);
            
            $table->string('doc_type'); // e.g., 'PSA_Birth', 'CENOMAR'
            $table->string('file_path'); // Path to the file in storage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
