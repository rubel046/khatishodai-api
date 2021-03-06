<?php

namespace App\Model;

class CompanyTradeMembership extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
