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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $guarded = [];*/
    protected $fillable = [
        'account_type',
        'userName',
        'first_name',
        'last_name',
        'photo',
        'job_title',
        'email',
        'phone',
        'telephone'
    ];



    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at', 'ip_address'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
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
