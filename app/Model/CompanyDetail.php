<?php

namespace App\Model;

class CompanyDetail extends Model
{
    protected $guarded = [];
    protected $fillable =['company_id', 'logo', 'about_us', 'mission', 'vision', 'youtube_link', 'fb_link', 'status', 'created_by', 'updated_by', 'ip_address'];
    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];
    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
