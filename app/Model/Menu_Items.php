<?php

namespace App\Model;

class Menu_Items extends Model
{
    protected $guarded = [];
    public function user_privileges()
    {
        return $this->belongsTo('App\Model\User_Privileges');
    }
}
