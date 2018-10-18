<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
    ];

    public function User()
    {
        $this->belongsTo('App\User', 'user_id');
    }
}
