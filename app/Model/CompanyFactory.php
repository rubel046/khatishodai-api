<?php

namespace App\Model;

class CompanyFactory extends Model
{
    protected $table = 'company_factories';
    protected $fillable = ['company_id', 'size_id', 'staff_number_id', 'rnd_staff_id', 'production_line_id', 'annual_output_id', 'status'];
    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];


    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function rnd_staff()
    {
        return $this->belongsTo('App\Model\RndStaffBreakdown');
    }

    public function address()
    {
        return $this->morphOne('App\Model\Address', 'addressable');
    }
}
