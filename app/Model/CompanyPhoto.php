<?php

namespace App\Model;

class CompanyPhoto extends Model
{
    //
    protected $fillable = ['company_id', 'photo', 'status', 'created_by', 'updated_by'];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
