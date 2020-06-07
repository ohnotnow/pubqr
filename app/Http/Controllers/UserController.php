<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('user.edit', [
            'user' => new User,
        ]);
    }

    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
        ]);
    }
}
