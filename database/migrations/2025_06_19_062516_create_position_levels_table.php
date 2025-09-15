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
        Schema::create('position_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level_name');           // Example: "Entry Level", "Managerial"
            $table->string('abbreviation');     // Example: "EL", "MG"
            $table->integer('level_rank');      // Example: 1 for lowest, 10 for highest
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_levels');
    }
};
