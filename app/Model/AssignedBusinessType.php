<?php

namespace App\Model;

class AssignedBusinessType extends Model
{
    protected $fillable = ['company_id','business_type_id', 'status', 'created_by', 'updated_by', 'created_at', 'ip_address' ];

    public function company()
    {
        return $this->belongsTo('App\Model\Company');
    }
    public function business_type()
    {
        return $this->belongsTo('App\Model\BusinessType');
    }

}
