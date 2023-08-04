<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeProgramCategoriesAddAutoincrement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE program_categories ADD id INT NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)');
        DB::statement('ALTER TABLE program_list_categories ADD id INT NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program_categories', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('program_list_categories', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
