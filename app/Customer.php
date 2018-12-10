<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Customer extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'customers';

    protected $fillable = [
        'image_url_array',
        'vector',
        'name',
        'email',
        'age',
        'gender',
        'telephone',
        'address',
        'favorites',
        'type',
        'note',
    ];
}
