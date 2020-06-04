<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Helper\CustomBlueprint;

class CreateAttributeGroupAssignedTermsTable extends Migration
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

        $schema->create('attribute_group_assigned_terms', function (CustomBlueprint $table) {
            $table->id();
            $table->integer('attribute_group_id')->unsigned()->index('attribute_group_id');
            $table->integer('attribute_id')->unsigned()->index('attribute_id');
            $table->string('attribute_term_ids',255);
            $table->boolean('is_visible_on_product')->default(false);
            $table->boolean('is_variation_maker')->default(false);
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
        Schema::dropIfExists('attribute_group_assigned_terms');
    }
}
