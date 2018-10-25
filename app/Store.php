<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Store extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'active'
    ];
    protected $hidden = ['password'];

    public function users()
    {
        return $this->belongsToMany('App\User', 'store_has_branches', 'store_id', 'branch_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address', 'owner_id');
    }
}
