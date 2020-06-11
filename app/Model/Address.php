<?php

namespace App\Model;


class Address extends Model
{
    protected $hidden = ['addressable_type', 'addressable_id', 'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];


    public function addressable()
    {
        return $this->morphTo();
    }

    public function country()
    {
        return $this->belongsTo('App\Model\Country');
    }

    public function division()
    {
        return $this->belongsTo('App\Model\Division');
    }

    public function city()
    {
        return $this->belongsTo('App\Model\City');
    }

    public function area()
    {
        return $this->belongsTo('App\Model\Area');
    }
}
