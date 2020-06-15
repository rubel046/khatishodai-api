<?php

use App\Model\SystemConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table("system_configs")->truncate();
        $fileConf = file_get_contents(base_path('systemconfig.json'));
        collect(json_decode($fileConf))->map(function ($sysConfData) {
            $sysConf = new SystemConfig();
            $sysConf->name = $sysConfData->name;
            $sysConf->alias = $sysConfData->alias;
            $sysConf->data = json_encode($sysConfData->data);
            $sysConf->status = 1;
            $sysConf->save();
        });

    }
}
