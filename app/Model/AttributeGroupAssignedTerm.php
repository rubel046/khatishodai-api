<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroupAssignedTerm extends Model
{
    use SoftDeletes;
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

