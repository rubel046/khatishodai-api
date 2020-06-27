<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',  'display_name', 'slug','type','status', 'ip_address','created_by', 'updated_by'
    ];

    public function attributeGroup()
    {
        return $this->hasMany('App\Model\AttributeTerm');
    }
}

