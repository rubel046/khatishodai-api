<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_Privileges extends Model
{
    use SoftDeletes;
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
