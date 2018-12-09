<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Camera extends Authenticatable
{
    use Notifiable;
    protected $connection = 'mysql';
    protected $fillable = [
        'branch_id',
        'name',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch', 'branch_id');
    }
}
