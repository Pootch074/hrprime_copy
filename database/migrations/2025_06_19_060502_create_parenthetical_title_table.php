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
        Schema::create('parenthetical_titles', function (Blueprint $table) {
            $table->id();
            $table->string('position_name');     // Title name
            $table->string('abbreviation');      // Short form
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parenthetical_titles');
    }
};
