<?php

namespace App\Model;

class CompanyNearestPort extends Model
{
    protected $fillable = ['name', 'company_id', 'address', 'status', 'ip_address'];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
