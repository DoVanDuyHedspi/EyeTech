<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'country',
        'city',
        'location',
    ];

    public function User()
    {
        $this->belongsTo('App\User', 'owner_id');
    }
}
