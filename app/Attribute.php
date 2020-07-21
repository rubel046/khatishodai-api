<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',  'display_name', 'slug','type','is_filter_criteria','status', 'ip_address','created_by', 'updated_by'
    ];
    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'
    ];

    public function attributeGroupAssignedTerm()
    {
        return $this->hasMany('App\Model\AttributeGroupAssignedTerm');
    }

    public function attributeTerms()
    {
        return $this->hasMany('App\Model\AttributeTerm');
    }
}

