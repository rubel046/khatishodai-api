<?php

namespace App\Model;

class Area extends Model
{
    //
    protected $fillable = ['country_id', 'name','code','status','created_by', 'updated_by'];

    public function country()
    {
        return $this->belongsTo('App\Model\Country');
    }
}
