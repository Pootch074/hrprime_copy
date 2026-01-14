<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Add salary_tranche_id as a foreign key
            $table->foreignId('salary_tranche_id')->nullable()->constrained('salary_tranche')->nullOnDelete();

            // Add salary_step_id as a foreign key
            $table->foreignId('salary_step_id')->nullable()->constrained('salary_step')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['salary_tranche_id']);
            $table->dropForeign(['salary_step_id']);

            // Then drop the columns
            $table->dropColumn(['salary_step']);
        });
    }
};
