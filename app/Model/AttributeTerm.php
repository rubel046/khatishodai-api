<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeTerm extends Model
{
    use SoftDeletes;
    protected $guarded = [];


    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }
}

