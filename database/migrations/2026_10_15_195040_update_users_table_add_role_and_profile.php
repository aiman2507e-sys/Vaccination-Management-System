<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Update Users Table
 * 
 * Adds role-based access control and contact profile fields
 * to support Admin, Parent, and Hospital user personas.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // User role: admin, parent, hospital
            $table->string('role')->default('parent')->after('email');
            
            // Primary contact telephone number
            $table->string('phone')->nullable()->after('role');
            
            // Physical street address
            $table->text('address')->nullable()->after('phone');
            
            // City or municipality
            $table->string('city')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'city']);
        });
    }
};
