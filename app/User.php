<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'active',
    ];

    protected $hidden = [
        'password',
    ];

    public function stores()
    {
        return $this->belongsToMany('App\Store', 'store_has_branches', 'branch_id', 'store_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address', 'owner_id');
    }
}
