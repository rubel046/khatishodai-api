<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Helper\CustomBlueprint;

class CreateCompanyFactoriesTable extends Migration
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

        $schema->create('company_factories', function (CustomBlueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('location',150)->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('staff_number_id')->nullable();
            $table->integer('rnd_staff_id')->nullable();
            $table->integer('production_line_id')->nullable();
            $table->integer('annual_output_id')->nullable();
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
        Schema::dropIfExists('company_factories');
    }
}
