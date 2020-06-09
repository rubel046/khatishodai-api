<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'company_basic_infos';
    protected $guarded = [];


    public function company_certificate()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyDetail()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyFactory()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyNearestPort()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyPhoto()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyProduct()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyTradeInfo()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyTradeMembership()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function operationalAddress()
    {
        return $this->morphOne('App\Model\Address', 'addressable')->where('address_type', 'operation');
    }

    public function registerAddress()
    {
        return $this->morphOne('App\Model\Address', 'addressable')->where('address_type', 'register');
    }
}
