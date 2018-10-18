<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'active',
    ];

    public function store()
    {
        $this->belongsTo('App\Store', 'store_id');
    }
    public function address()
    {
        $this->hasOne('App\Address', 'owner_id');
    }
    public function cameras()
    {
        $this->hasMany('App\Camera', 'user_id');
    }
}
