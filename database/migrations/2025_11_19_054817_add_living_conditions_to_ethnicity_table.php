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
    Schema::table('ethnicity', function (Blueprint $table) {
      $table->text('description')->nullable()->after('special_needs');
      $table->string('living_condition')->nullable()->after('description');
      $table->string('living_condition_other')->nullable()->after('living_condition');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('ethnicity', function (Blueprint $table) {
      $table->dropColumn(['description', 'living_condition', 'living_condition_other']);
    });
  }
};
