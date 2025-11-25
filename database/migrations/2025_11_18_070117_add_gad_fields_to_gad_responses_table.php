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
    Schema::table('gad_responses', function (Blueprint $table) {
      $table->json('gad_challenges')->nullable();
      $table->json('gad_trainings')->nullable();
      $table->json('intervention_modes')->nullable();
    });
  }

  public function down(): void
  {
    Schema::table('gad_responses', function (Blueprint $table) {
      $table->dropColumn(['gad_challenges', 'gad_trainings', 'intervention_modes']);
    });
  }
};
