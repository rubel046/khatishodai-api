<?php

namespace App\Model;

class Operations extends Model
{
    protected $guarded = [];
   // protected $fillable = [ 'name', ];
    public function user_privileges()
    {
        return $this->belongsTo('App\Model\User_Privileges');
    }
}
