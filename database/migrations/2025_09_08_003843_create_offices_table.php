<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('offices', function (Blueprint $table) {
      $table->id(); // BIGINT UNSIGNED (primary key)

      $table->string('code')->unique();  // e.g. "HRD", "FIN"
      $table->string('name');            // e.g. "Human Resource Division"
      $table->string('description')->nullable();

      // Optional hierarchy (if offices have parent/child sections)
      $table->foreignId('parent_id')
        ->nullable()
        ->constrained('offices')
        ->nullOnDelete();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('offices');
  }
};
