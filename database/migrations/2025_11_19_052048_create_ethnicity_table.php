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
    Schema::create('ethnicity', function (Blueprint $table) {
      $table->id();

      // Employee ID (string or integer depending on your system)
      $table->string('empid')->index();

      // Ethnicity name
      $table->string('ethnicity')->nullable();

      // Demographic counts
      $table->integer('household_count')->default(0);
      $table->integer('zero_above')->default(0);
      $table->integer('six_above')->default(0);
      $table->integer('eighteen_above')->default(0);
      $table->integer('forty_six_above')->default(0);
      $table->integer('sixty_above')->default(0);

      // Additional attributes
      $table->integer('children_still_studying')->default(0);
      $table->boolean('special_needs')->default(false);

      // Timestamps
      $table->timestamp('created_on')->nullable();
      $table->timestamp('updated_on')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ethnicity');
  }
};
