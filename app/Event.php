<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Event extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'customer_id',
        'vector',
        'time_in',
        'camera_id',
        'image_camera_url_array',
        'image_detection_url_array',
        'emotion',
    ];
}
