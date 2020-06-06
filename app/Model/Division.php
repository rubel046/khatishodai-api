<?php

namespace App\Model;


class Division extends Model
{
    protected $guarded = [];

    public function city()
    {
        return $this->hasMany('App\Model\City');
    }
}
