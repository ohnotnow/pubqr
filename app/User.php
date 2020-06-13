<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'can_login' => 'boolean',
        'is_superadmin' => 'boolean',
        'login_at' => 'datetime',
        'force_reset_password' => 'boolean',
    ];

    public function updateLoginDate()
    {
        $this->login_at = now();
        $this->save();
    }

    public function isSuperAdmin()
    {
        return $this->is_superadmin;
    }

    public function canLogIn()
    {
        return $this->can_login;
    }

    public function toggleSuperAdmin()
    {
        $this->is_superadmin = ! $this->is_superadmin;
        $this->save();
    }

    public function toggleCanLogIn()
    {
        $this->can_login = ! $this->can_login;
        $this->save();
    }

    public function mustChangeTheirPassword()
    {
        return $this->force_reset_password;
    }
}
