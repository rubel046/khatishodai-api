<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Helper\CustomBlueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function ($table, $callback) {
            return new CustomBlueprint($table, $callback);
        });

        $schema->create('users', function (CustomBlueprint $table) {
            $table->id();
            $table->tinyInteger('account_type')->nullable()->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->string('userName',100)->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('photo',150)->nullable();
            $table->string('job_title',150)->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->string('phone',100)->unique()->nullable();
            $table->string('telephone',100)->nullable();
            $table->text('address')->nullable();
            $table->string('city_id',100)->nullable();
            $table->string('zone_id',100)->nullable();
            $table->string('division_id',100)->nullable();
            $table->string('district_id',100)->nullable();
            $table->integer('country_id')->nullable();
            $table->mediumText('verificationToken')->nullable();
            $table->tinyInteger('is_verified')->nullable()->unsigned();
            $table->tinyInteger('is_admin')->default(0)->unsigned();

            $table->commonFields();
        });

         //now the data migration
        Artisan::call('db:seed', [
            '--class' => UserSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
