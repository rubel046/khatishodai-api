<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroup extends Model
{
    use SoftDeletes;
    protected $guarded = [];


    public function group_assigned_term()
    {
        return $this->hasMany('App\Model\GroupAssignedTerm');
    }

}
