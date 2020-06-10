<?php

namespace App\Model;

class AttributeTerm extends Model
{
    protected $guarded = [];


    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }
}

