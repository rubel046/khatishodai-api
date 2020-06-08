<?php

namespace App\Model;


class Country extends Model
{
    protected $guarded = [];
    //protected $fillable = ['name','code'];

    public function division()
    {
        return $this->hasMany('App\Model\Division');
    }
}
