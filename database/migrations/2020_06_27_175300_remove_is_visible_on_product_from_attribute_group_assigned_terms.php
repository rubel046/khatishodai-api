<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsVisibleOnProductFromAttributeGroupAssignedTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_group_assigned_terms', function (Blueprint $table) {
            $table->dropColumn([ 'is_visible_on_product' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_group_assigned_terms', function (Blueprint $table) {
            //
        });
    }
}
