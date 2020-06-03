<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    //protected $fillable = ['name','code'];
    public function division()
    {
        return $this->hasMany('App\Model\Division');
    }
}
