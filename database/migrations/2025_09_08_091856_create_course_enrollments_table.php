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
    Schema::create('course_enrollments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade'); // links to users
      $table->foreignId('course_id')->constrained()->onDelete('cascade'); // links to courses
      $table->enum('status', ['pending', 'in_progress', 'done'])->default('pending');
      $table->integer('score')->nullable();
      $table->integer('time_spent')->nullable(); // in seconds
      $table->timestamp('completed_at')->nullable();
      $table->timestamps();

      $table->unique(['user_id', 'course_id']); // Prevent duplicate enrollments
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('course_enrollments');
  }
};
