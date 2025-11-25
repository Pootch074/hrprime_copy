<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('gad_profiles', function (Blueprint $table) {
      $table->id();
      $table->string('empid')->nullable();
      $table->string('gender')->nullable();
      $table->string('honorifics')->nullable();
      $table->string('other_honorifics')->nullable();
      $table->boolean('move_member')->default(0);
      $table->boolean('gfps_twg')->default(0);

      // Questions q1 – q26
      for ($i = 1; $i <= 26; $i++) {
        $table->string("q$i")->nullable();
      }

      $table->string('desired_mode')->nullable();
      $table->timestamp('submitted_at')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('gad_profiles');
  }
};
