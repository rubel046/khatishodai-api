<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyFactory extends Model
{
    //
    use SoftDeletes;
    protected $table = 'company_factories';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
