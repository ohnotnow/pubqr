<?php

namespace App\Http\Livewire;

use App\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserEditor extends Component
{
    public $user;

    public $editingExistingUser = false;

    public $reset_password = false;

    public function mount(array $user)
    {
        $this->user = $user;
        $this->editingExistingUser = isset($user['id']);
    }

    public function render()
    {
        return view('livewire.user-editor');
    }

    public function save()
    {
        $this->validate($this->getValidationRules());

        if ($this->editingExistingUser) {
            $user = User::findOrFail($this->user['id']);
        } else {
            $user = new User;
            $user->password = bcrypt(Str::random(64));
            $user->can_login = true;
        }

        $user->email = $this->user['email'];
        $user->name = $this->user['name'];
        $user->save();

        if ($this->reset_password) {
            $response = $this->broker()->sendResetLink(['email' => $user->email]);
        }

        return redirect(route('user.index'));
    }

    protected function getValidationRules()
    {
        if ($this->editingExistingUser) {
            return [
                'user.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user['id'])],
                'user.name' => 'required',
            ];
        }

        return [
            'user.email' => ['required', 'email', Rule::unique('users', 'email')],
            'user.name' => 'required',
        ];
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected function broker()
    {
        return Password::broker();
    }
}
