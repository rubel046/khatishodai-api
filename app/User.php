<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'username', 'password', 'name', 'first_name', 'last_name', 'email', 'phone', 'verification_token', 'is_verified',
        'status'
    ];

    protected $hidden = [
        'password', 'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user_privileges()
    {
        return $this->belongsTo('App\Model\User_Privileges');
    }

    public function company()
    {
        return $this->hasOne('App\Model\Company');
    }

    public function address()
    {
        return $this->morphOne('App\Model\Address', 'addressable');
    }

}
