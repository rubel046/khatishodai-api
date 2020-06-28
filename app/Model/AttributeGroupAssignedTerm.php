<?php

namespace App\Model;
class AttributeGroupAssignedTerm extends Model
{
    protected $fillable = ['attribute_group_id', 'attribute_id', 'attribute_term_ids', 'is_variation_maker', 'status', 'created_by', 'ip_address'];
    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];

    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }

    public function attribute_group()
    {
        return $this->belongsTo('App\AttributeGroup');
    }
}

