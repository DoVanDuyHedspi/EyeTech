<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'active',
    ];

    public function users()
    {
        return $this->hasMany('App\User', 'store_id');
    }
}
