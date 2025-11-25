<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('tbl_travel', function (Blueprint $table) {
      $table->string('travel_reference_number')->nullable()->after('id_travel');
    });
  }

  public function down(): void
  {
    Schema::table('tbl_travel', function (Blueprint $table) {
      $table->dropColumn('travel_reference_number');
    });
  }
};
