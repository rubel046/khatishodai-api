<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function city()
    {
        return $this->hasMany('App\Model\City');
    }
}
