<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('applicants', function (Blueprint $table) {
      $table->date('examination_date')->nullable()->after('date_applied');
      $table->date('date_interviewed')->nullable()->after('examination_date');
    });
  }

  public function down(): void
  {
    Schema::table('applicants', function (Blueprint $table) {
      $table->dropColumn(['examination_date', 'date_interviewed']);
    });
  }
};
