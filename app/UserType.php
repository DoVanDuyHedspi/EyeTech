<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserType extends Model
{
    use Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'type'
    ];
}
