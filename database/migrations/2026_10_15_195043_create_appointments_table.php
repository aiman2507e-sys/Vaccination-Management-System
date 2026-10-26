<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Appointments Table
 * 
 * Central workflow table connecting Parents, Children, Vaccines, and Hospitals.
 * Workflow:
 * 1. Parent books hospital -> status: pending
 * 2. Admin reviews -> status: approved or rejected
 * 3. Hospital receives approved appointment -> status updated to vaccinated or not_vaccinated
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference', 20)->unique();
            
            // Relational associations
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('vaccine_id')->constrained('vaccines')->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            
            // Scheduling details
            $table->date('appointment_date');
            $table->string('appointment_time', 50)->default('09:00 AM - 10:00 AM');
            
            // Lifecycle status: pending -> approved/rejected -> vaccinated/not_vaccinated
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'vaccinated',
                'not_vaccinated'
            ])->default('pending');
            
            // Admin review data
            $table->text('admin_notes')->nullable();
            $table->timestamp('admin_action_at')->nullable();
            
            // Hospital clinical administration record
            $table->date('administered_date')->nullable();
            $table->string('batch_number', 50)->nullable();
            $table->string('administered_by')->nullable();
            $table->text('hospital_remarks')->nullable();
            
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
