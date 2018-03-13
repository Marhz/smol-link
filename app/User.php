<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'confirmation_token'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isConfirmed()
    {
        return ($this->confirmation_token === null);
    }
    
    public function urls()
    {
        return $this->hasMany(Url::class);
    }
}
