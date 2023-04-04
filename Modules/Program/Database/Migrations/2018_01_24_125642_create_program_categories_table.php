<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_categories', function (Blueprint $table) {
            $table->integer('program_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->primary(['program_id', 'category_id']);
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_categories');
    }
}
