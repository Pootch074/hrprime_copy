<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('medical', function (Blueprint $table) {
      $table->string('autoimmune_other')->nullable()->after('autoimmune');
      $table->string('cancer_other')->nullable()->after('cancer');
      $table->string('mental_health_other')->nullable()->after('mental_health');
      $table->string('health_condition_other')->nullable()->after('health_condition');
    });
  }

  public function down(): void
  {
    Schema::table('medical', function (Blueprint $table) {
      $table->dropColumn([
        'autoimmune_other',
        'cancer_other',
        'mental_health_other',
        'health_condition_other'
      ]);
    });
  }
};
