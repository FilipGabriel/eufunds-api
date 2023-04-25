<?php

/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.12
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationCartalystSentinel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nod_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('manager_name')->nullable();
            $table->string('manager_email')->nullable();
            $table->string('phone')->nullable();
            $table->text('about')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->text('permissions')->nullable();
            $table->datetime('last_login')->nullable();
            $table->longText('remember_token')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE users ADD FULLTEXT(name)');

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('permissions')->nullable();
            $table->timestamps();
        });

        Schema::create('role_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('role_id');
            $table->string('locale');
            $table->string('name');

            $table->unique(['role_id', 'locale']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('persistences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('code')->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('code');
            $table->boolean('completed')->nullable()->default(false);
            $table->datetime('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('throttle', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type');
            $table->string('ip')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('throttle');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('persistences');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_translations');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
    }
}
