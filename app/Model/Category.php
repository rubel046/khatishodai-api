<?php

namespace App\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $with = ['children'];

    //protected $fillable = ['name','code'];

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
