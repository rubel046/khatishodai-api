<?php

namespace App\Model;

class BusinessType extends Model
{
    protected $fillable = ['name', 'status', 'created_by', 'updated_by', 'created_at', 'ip_address'];

    public function companies()
    {
        return $this->belongsToMany('App\Model\Company', 'company_business_types', 'business_type_id', 'company_id')->withPivot( 'created_by', 'updated_by', 'ip_address')->withTimestamps();
    }
}
