<?php

namespace App\Model;

class CompanyTradeInfo extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function export_percentage()
    {
        return $this->belongsTo('App\Model\ExportPercentageBreakdown');
    }
}
