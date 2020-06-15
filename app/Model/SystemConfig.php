<?php

namespace App\Model;

class SystemConfig extends Model
{
    protected $fillable = ['name', 'alias', 'purpose', 'data', 'status'];
    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'];

    public function getDataAttribute($value)
    {
         return json_decode($value, true);
    }

}
