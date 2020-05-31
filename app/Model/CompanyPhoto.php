<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPhoto extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
