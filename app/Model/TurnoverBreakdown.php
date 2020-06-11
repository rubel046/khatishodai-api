<?php

namespace App\Model;

class TurnoverBreakdown extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->hasMany('App\Model\Company');
    }
}
