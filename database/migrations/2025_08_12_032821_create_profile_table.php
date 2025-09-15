<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Link to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic Information
            $table->string('place_of_birth')->nullable();
            $table->string('civil_status')->nullable();
            $table->decimal('height', 5, 2)->nullable(); // in meters
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->string('blood_type', 3)->nullable();
            $table->string('tel_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('country')->nullable();

            // Permanent Address
            $table->string('perm_region')->nullable();
            $table->string('perm_province')->nullable();
            $table->string('perm_city')->nullable();
            $table->string('perm_barangay')->nullable();
            $table->string('perm_block_street_purok')->nullable();
            $table->string('perm_house_no')->nullable();
            $table->string('perm_subd_village')->nullable();
            $table->string('perm_zipcode')->nullable();

            // Residence Address
            $table->string('res_region')->nullable();
            $table->string('res_province')->nullable();
            $table->string('res_city')->nullable();
            $table->string('res_barangay')->nullable();
            $table->string('res_block_street_purok')->nullable();
            $table->string('res_house_no')->nullable();
            $table->string('res_subd_village')->nullable();
            $table->string('res_zipcode')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
