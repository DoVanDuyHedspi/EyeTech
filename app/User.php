<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;
    protected $connection = 'mysql';
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'type',
        'active',
    ];

    protected $hidden = [
        'password',
    ];

    public function store()
    {
        return $this->hasOne('App\Store', 'user_id');
    }
    public function branch()
    {
        return $this->hasOne('App\Branch', 'user_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address', 'owner_id');
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setActive()
    {
        if ($this->isActive() == false) {
            $this->active = !$this->active;
        }
    }
}
