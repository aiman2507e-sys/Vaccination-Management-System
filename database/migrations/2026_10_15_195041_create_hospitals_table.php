<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Hospitals Table
 * 
 * Stores registered healthcare centers and vaccination clinics.
 * Hospitals can be added by Admins or registered directly by hospital management.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            
            // Optional user account link for hospital portal login
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Hospital Identification details
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->string('city')->default('Metropolis');
            $table->string('location')->comment('Area, district, or landmark');
            
            // Operational Information
            $table->string('contact_person')->nullable();
            $table->string('operating_hours')->default('08:00 AM - 04:00 PM');
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
