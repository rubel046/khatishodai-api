<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Helper\CustomBlueprint;

class CreateProductsTable extends Migration
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

        $schema->create('products', function (CustomBlueprint $table) {
            $table->id();
            $table->string('sku',10);
            $table->integer('product_type_id');
            $table->integer('category_id');
            $table->integer('brand_id');
            $table->integer('supplier_id');
            $table->string('code',10);
            $table->string('name',100);
            $table->tinyInteger('min_quantity')->nullable();
            $table->longText('intro')->nullable();
            $table->string('description',255)->nullable();
            $table->tinyInteger('unit_id');
            $table->tinyInteger('is_catalog')->default(0);
            $table->tinyInteger('catalog_id')->nullable();
            $table->tinyInteger('is_online_purchase')->default(0);
            $table->tinyInteger('is_approved')->default(0);
            $table->date('approved_date');
            $table->integer('approved_by');
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
        Schema::dropIfExists('products');
    }
}
