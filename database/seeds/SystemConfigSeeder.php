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
        //$clinet = new \GuzzleHttp\Client();
        $fileConf = file_get_contents(base_path('systemconfig.json'));
        //$sysConfData = collect(json_decode($fileConf));

        collect(json_decode($fileConf))->map(function ($sysConfData){
            $sysConf = new SystemConfig();
            $sysConf->name = $sysConfData->name;
            $sysConf->alias = $sysConfData->alias;
            $sysConf->data = $sysConfData->data;
            $sysConf->status = 1;
            $sysConf->save();
        });
    }
}
