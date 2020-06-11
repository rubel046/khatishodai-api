<?php

namespace App\Model;
class AttributeGroupAssignedTerm extends Model
{
    protected $guarded = [];


    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }

    public function attribute_group()
    {
        return $this->belongsTo('App\AttributeGroup');
    }
}

