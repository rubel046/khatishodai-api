<?php

namespace App\Model;

class User_Privileges extends Model
{
    protected $guarded = [];
    public function operations()
    {
        return $this->hasMany('App\Model\Operations');
    }
    public function menu_items()
    {
        return $this->hasMany('App\Model\Menu_Items');
    }
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
