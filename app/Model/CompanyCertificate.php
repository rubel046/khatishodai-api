<?php

namespace App\Model;

class CompanyCertificate extends Model
{
    protected $fillable = ['company_id', 'name', 'created_by', 'updated_by', 'reference_number', 'issued_by', 'start_date', 'end_date', 'certificate_photo_name', 'status'];


    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
