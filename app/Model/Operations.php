<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operations extends Model
{
    use SoftDeletes;
    protected $guarded = [];
   // protected $fillable = [ 'name', ];
    public function user_privileges()
    {
        return $this->belongsTo('App\Model\User_Privileges');
    }
}
