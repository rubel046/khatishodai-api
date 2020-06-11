<?php

namespace App\Model;

class CompanyFactory extends Model
{
    protected $table = 'company_factories';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function rnd_staff()
    {
        return $this->belongsTo('App\Model\RndStaffBreakdown');
    }
}
