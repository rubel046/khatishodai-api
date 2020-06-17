<?php

namespace App\Model;


class CompanyProduct extends Model
{
    //
    protected $fillable = ['name', 'company_id', 'is_main', 'status', 'created_by'];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
