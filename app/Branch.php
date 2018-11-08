<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Branch extends Authenticatable
{
    use Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'user_id',
        'store_id',
        'name',
        'email',
        'password',
        'telephone',
    ];
    protected $hidden = [
        'password',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address', 'owner_id');
    }
}
