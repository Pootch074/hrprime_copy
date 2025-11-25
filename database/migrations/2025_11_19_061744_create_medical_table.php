<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('medical', function (Blueprint $table) {
      $table->id();
      $table->string('empid')->unique(); // Employee ID
      $table->string('blood_type')->nullable();
      $table->string('qualified_blood_donation')->nullable();
      $table->string('blood_donation')->nullable();
      $table->string('asthma')->nullable();
      $table->string('autoimmune')->nullable();
      $table->string('cancer')->nullable();
      $table->string('diabetes_mellitus')->nullable();
      $table->string('heart_disease')->nullable();
      $table->string('hiv_aids')->nullable();
      $table->string('hypertension')->nullable();
      $table->string('kidney_disease')->nullable();
      $table->string('liver_disease')->nullable();
      $table->string('mental_health')->nullable();
      $table->string('seizures')->nullable();
      $table->string('health_condition')->nullable();
      $table->string('maintenance_med')->nullable();
      $table->string('disability_type')->nullable();
      $table->string('disability_cause')->nullable();
      $table->timestamp('created_on')->nullable();
      $table->timestamp('updated_on')->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('medical');
  }
};
