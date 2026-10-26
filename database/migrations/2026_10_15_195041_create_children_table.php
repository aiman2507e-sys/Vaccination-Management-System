<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Children Table
 * 
 * Stores profile records of infants and children registered by parents.
 * Contains demographic, physical, and medical background data required
 * for accurate pediatric vaccination schedules.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            
            // Parent foreign relationship
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            
            // Demographic information
            $table->string('name');
            $table->date('dob')->comment('Date of Birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('blood_group', 10)->default('O+');
            
            // Physical & Medical details
            $table->decimal('birth_weight', 4, 2)->nullable()->comment('Weight in kilograms');
            $table->string('birth_place')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
