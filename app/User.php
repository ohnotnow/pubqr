<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

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

    public function isSuperAdmin(): bool
    {
        return $this->is_superadmin;
    }

    public function canLogIn(): bool
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

    public function mustChangeTheirPassword(): bool
    {
        return $this->force_reset_password;
    }
}
