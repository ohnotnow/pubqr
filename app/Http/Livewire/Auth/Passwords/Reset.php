<?php

namespace App\Http\Livewire\Auth\Passwords;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class Reset extends Component
{
    public $password;

    public $passwordConfirmation;

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:12|same:passwordConfirmation',
        ]);

        if (Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Cannot be the same as your current one');
            return;
        }

        auth()->user()->password = bcrypt($this->password);
        auth()->user()->force_reset_password = false;
        auth()->user()->save();

        session()->flash('message', 'Password reset!');

        return redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.passwords.reset');
    }
}
