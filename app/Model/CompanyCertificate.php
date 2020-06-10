<?php

namespace App\Model;

class CompanyCertificate extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
