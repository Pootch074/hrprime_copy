<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::table('cs_eligibilities', function (Blueprint $table) {
                $table->decimal('rating', 5, 2)->nullable()->change();
            });
        }

        public function down()
        {
            Schema::table('cs_eligibilities', function (Blueprint $table) {
                $table->string('rating')->nullable()->change();
            });
        }


};
