<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Helper\CustomBlueprint;

class CreateAddressesTable extends Migration
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

        $schema->create('addresses', function (CustomBlueprint $table) {
            $table->id();
            $table->integer('addressable_id')->comment('Module Model Id');
            $table->string('addressable_type',100)->comment('Module Model Class');
            $table->integer('country_id')->index();
            $table->integer('division_id')->index();
            $table->integer('city_id')->index();
            $table->integer('area_id')->index();
            $table->string('address');
            $table->string('zip_code')->nullable();
            $table->string('address_type',32)->nullable()->comment('operation, register, present, permanent');
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
        Schema::dropIfExists('addresses');
    }
}
