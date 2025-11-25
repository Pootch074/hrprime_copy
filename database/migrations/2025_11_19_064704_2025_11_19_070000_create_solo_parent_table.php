<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('solo_parent', function (Blueprint $table) {
      $table->id();
      $table->string('empid')->index();
      $table->string('circumstance')->nullable();
      $table->string('circumstance_other')->nullable();
      $table->string('status')->nullable();
      $table->timestamp('created_on')->nullable();
      $table->timestamp('updated_on')->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('solo_parent');
  }
};
