<?php

namespace App\Model;

class AttributeTerm extends Model
{
    protected $fillable = ['attribute_id', 'name', 'is_visible_on_product', 'status', 'created_by', 'ip_address'];
    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];


    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }
}

