<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
            Schema::create('is_vacant', function (Blueprint $table) {
            $table->id();

            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->foreignId('qualification_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('is_vacant');
    }
};

