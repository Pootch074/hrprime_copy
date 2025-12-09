<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('cprs', function (Blueprint $table) {
      $table->date('rating_period_start')->nullable();
      $table->string('semester')->nullable();
    });
  }

  public function down(): void
  {
    Schema::table('cprs', function (Blueprint $table) {
      $table->dropColumn(['rating_period_start', 'semester']);
    });
  }
};
