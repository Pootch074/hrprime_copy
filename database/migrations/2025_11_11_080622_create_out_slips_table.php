<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('out_slips', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->string('empid', 20);
      $table->string('destination');
      $table->string('type_of_slip', 100);
      $table->text('purpose')->nullable();
      $table->string('status', 50)->default('Pending');

      // Store the user ID of the approver
      $table->foreignId('approved_by')->nullable()
        ->constrained('users') // references id in users table
        ->nullOnDelete();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('out_slips');
  }
};
