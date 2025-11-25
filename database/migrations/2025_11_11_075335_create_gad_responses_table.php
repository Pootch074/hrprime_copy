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
    Schema::create('gad_responses', function (Blueprint $table) {
      $table->id();
      $table->string('empid', 10);
      $table->string('gender', 50);
      $table->string('honorifics', 100);
      $table->string('move_member', 10);
      $table->string('gfps_twg', 10);

      // Questions q1 - q26 as tiny integers (booleans or small numeric ratings)
      for ($i = 1; $i <= 26; $i++) {
        $table->tinyInteger('q' . $i);
      }

      $table->string('desired_mode', 100);
      $table->timestamp('submitted_at')->useCurrent();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('gad_responses');
  }
};
