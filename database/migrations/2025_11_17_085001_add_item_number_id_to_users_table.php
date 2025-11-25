<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->foreignId('item_number_id')
        ->nullable()
        ->after('id') // adjust position if needed
        ->constrained('item_numbers') // sets up foreign key
        ->onDelete('set null'); // optional: set to null if item number deleted
    });
  }

  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['item_number_id']);
      $table->dropColumn('item_number_id');
    });
  }
};
