<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Helper\CustomBlueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $table->string('password');
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('photo',150)->nullable();
            $table->string('job_title',150)->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->string('phone',100)->unique()->nullable();
            $table->string('telephone',100)->nullable();
            $table->text('address')->nullable();
            $table->string('district',100)->nullable();
            $table->string('division',100)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('zipcode',100)->nullable();
            $table->mediumText('verificationToken')->nullable();
            $table->tinyInteger('is_verified')->nullable()->unsigned();
            $table->tinyInteger('is_admin')->default(0)->unsigned();

            $table->commonFields();
        });
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
