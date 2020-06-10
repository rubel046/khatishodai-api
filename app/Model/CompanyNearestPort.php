<?php

namespace App\Model;

class CompanyNearestPort extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
