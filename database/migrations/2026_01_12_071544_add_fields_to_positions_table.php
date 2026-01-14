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
       Schema::table('positions', function (Blueprint $table) {
    $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('section_id')->nullable()->constrained()->nullOnDelete();
    $table->string('program')->nullable();
    $table->foreignId('office_location_id')->nullable()->constrained('office_locations')->nullOnDelete();
    $table->string('parenthetical_title')->nullable();
    $table->string('level')->nullable();
    $table->integer('salary_step')->nullable();
    $table->decimal('monthly_rate', 12,2)->nullable();
    $table->string('designation')->nullable();
    $table->string('special_order')->nullable();
    $table->string('obsu')->nullable();
    $table->string('fund_source')->nullable();
    $table->enum('type_of_request', ['Direct Release','CMF'])->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['office_location_id']);

            $table->dropColumn([
                'division_id',
                'section_id',
                'office_location_id',
                'program',
                'parenthetical_title',
                'level',
                'salary_step',
                'monthly_rate',
                'designation',
                'special_order',
                'fund_source',
                'type_of_request',
            ]);

            // Restore official_station as nullable string
            $table->string('official_station')->nullable();
        });
    }
};
