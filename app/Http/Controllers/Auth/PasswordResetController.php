<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{
    public function __invoke()
    {
        return view('auth.passwords.reset');
    }
}
