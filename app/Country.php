<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'name'
    ];

    public function cities()
    {
        $this->hasMany('App\City', 'country_id');
    }
}
