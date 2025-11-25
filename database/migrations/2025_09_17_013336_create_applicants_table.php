<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('applicants', function (Blueprint $table) {
      $table->id();
      $table->string('item_number')->nullable();
      $table->string('applicant_no')->nullable();
      $table->string('first_name');
      $table->string('middle_name')->nullable();
      $table->string('last_name');
      $table->string('extension_name')->nullable();
      $table->enum('sex', ['Male', 'Female']);
      $table->date('date_of_birth');
      $table->date('date_applied');
      $table->string('status'); // Pending, Hired, etc.
      $table->text('remarks')->nullable();
      $table->date('date_hired')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('applicants');
  }
};
