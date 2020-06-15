<?php

namespace App\Http\Controllers;

class ConfigController extends Controller
{
    private $configData;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
         $this->configData['yes_no']=['label'=>['Yes','No']];
         $this->configData['status']=['label'=>[1=>'Active',0=>'Inactive']];
         $this->configData['gender']=['label'=>[1=>'male',2=>'Female']];
         $this->configData['ownership_type']=['label'=>['Public','Private']];
         $this->configData['membership_plan']=['label'=>['Silver', 'Gold', 'Platinum', 'Bronze']];
         $this->configData['number_of_employee']=['label'=>['1-50', '50-100', '100-500', '500-1000', '1000-1500', '1500-2000']];
         $this->configData['factory_sizes']=['Small','Large'];

        return response()->json($this->configData, 200);
    }


}