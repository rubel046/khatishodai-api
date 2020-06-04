<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Helper\CustomBlueprint;

class CreateCompanyBasicInfosTable extends Migration
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

        $schema->create('company_basic_infos', function (CustomBlueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name',150);
            $table->string('display_name',150)->nullable();
            $table->date('establishment_date')->nullable();
            $table->text('office_space')->nullable();
            $table->text('operation_address')->nullable();
            $table->string('website',150)->nullable();
            $table->string('email',150)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('cell',50)->nullable();
            $table->string('fax',50)->nullable();
            $table->integer('number_of_employee')->nullable();
            $table->tinyInteger('ownership_type')->nullable();
            $table->integer('turnover_id')->nullable();
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
        Schema::dropIfExists('company_basic_infos');
    }
}
