<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Feedback extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'feedbacks';

    protected  $fillable = [
        'event_id',
        'status',
    ];
}
