<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu_Items extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function user_privileges()
    {
        return $this->belongsTo('App\Model\User_Privileges');
    }
}
