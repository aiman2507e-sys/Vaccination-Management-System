<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Vaccines Table
 * 
 * Stores the standardized pediatric immunization registry.
 * Admins can manage availability status (available / unavailable)
 * and add or update vaccine details.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            
            // Vaccine nomenclature
            $table->string('name');
            $table->string('short_code', 30)->unique();
            $table->string('target_disease');
            
            // Scheduling criteria
            $table->integer('recommended_age_days')->default(0)->comment('Age in days for automated schedule calculations');
            $table->string('recommended_age_label')->comment('E.g., At Birth, 6 Weeks, 10 Weeks, 9 Months');
            
            // Clinical specifications
            $table->string('dosage')->default('0.5 ml');
            $table->string('route')->default('Intramuscular (IM)');
            
            // Availability Flag as mandated in SRS
            $table->enum('status', ['available', 'unavailable'])->default('available');
            
            // Descriptive and clinical guidelines
            $table->text('description')->nullable();
            $table->text('precautions')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
