<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('tbl_special', function (Blueprint $table) {
      $table->string('special_ref')
        ->nullable()
        ->unique()
        ->after('id_special');
    });
  }

  public function down()
  {
    Schema::table('tbl_special', function (Blueprint $table) {
      $table->dropColumn('special_ref');
    });
  }
};
