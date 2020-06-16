<?php

namespace App\Model;

class CompanyTradeInfo extends Model
{
    protected $fillable = ['annual_revenue_id', 'company_id', 'export_percent_id', 'export_started_year', 'ip_address', 'status'];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function export_percentage()
    {
        return $this->belongsTo('App\Model\ExportPercentageBreakdown');
    }
}
