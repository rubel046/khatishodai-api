<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('city_id')->nullable()->change();
            $table->integer('area_id')->nullable()->change();
            $table->integer('division_id')->nullable()->change();
            $table->integer('district_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('city_id')->nullable()->change();
            $table->string('area_id')->nullable()->change();
            $table->string('division_id')->nullable()->change();
            $table->string('district_id')->nullable()->change();
        });
    }
}
