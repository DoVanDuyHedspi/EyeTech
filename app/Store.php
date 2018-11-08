<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Store extends Authenticatable
{
    use Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'telephone'
    ];
    protected $hidden = [
        'password'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function branches()
    {
        return $this->hasMany('App\Branch', 'store_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address', 'owner_id');
    }
}
