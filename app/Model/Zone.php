<?php

namespace App\Model;

class Zone extends Model
{
    //
    protected $fillable = ['country_id', 'name','code','status'];

    public function country()
    {
        return $this->belongsTo('App\Model\Country');
    }
}
