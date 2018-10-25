<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
    ];

    public function country()
    {
        $this->belongsTo('App\Country', 'country_id');
    }
}
