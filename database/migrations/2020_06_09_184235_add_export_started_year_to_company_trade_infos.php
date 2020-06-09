<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExportStartedYearToCompanyTradeInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_trade_infos', function (Blueprint $table) {
            $table->integer('export_started_year')->after('annual_revenue_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_trade_infos', function (Blueprint $table) {
            $table->dropColumn(['export_started_year']);
        });
    }
}
