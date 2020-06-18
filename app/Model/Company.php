<?php

namespace App\Model;

class Company extends Model
{
    protected $table = 'company_basic_infos';
    protected $guarded = [];
    protected $fillable = ['name','user_id','display_name','establishment_date','office_space','website','email','phone','cell','fax','number_of_employee','ownership_type','turnover_id','status','created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];
    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];


    public function company_certificate()
    {
        return $this->hasMany('App\Model\CompanyCertificate', 'company_id');
    }

    public function CompanyDetail()
    {
        return $this->hasOne('App\Model\CompanyDetail', 'company_id');
    }

    public function CompanyFactory()
    {
        return $this->hasMany('App\Model\CompanyFactory', 'company_id');
    }

    public function CompanyNearestPort()
    {
        return $this->hasMany('App\Model\CompanyNearestPort', 'company_id');
    }

    public function CompanyPhoto()
    {
        return $this->hasMany('App\Model\CompanyPhoto', 'company_id');
    }

    public function CompanyProduct()
    {
        return $this->hasMany('App\Model\CompanyProduct', 'company_id');
    }

    public function CompanyTradeInfo()
    {
        return $this->hasMany('App\Model\CompanyTradeInfo', 'company_id');
    }

    public function CompanyTradeMembership()
    {
        return $this->hasMany('App\Model\CompanyTradeMembership', 'company_id');
    }

    public function operationalAddress()
    {
        return $this->morphOne('App\Model\Address', 'addressable')->where('address_type', 'operation');
    }

    public function registerAddress()
    {
        return $this->morphOne('App\Model\Address', 'addressable')->where('address_type', 'register');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function businessTypes()
    {
        return $this->belongsToMany('App\Model\BusinessType','company_business_types','company_id','business_type_id')->withPivot( 'created_by', 'updated_by', 'ip_address');
    }
}
