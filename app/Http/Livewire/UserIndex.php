<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;

class UserIndex extends Component
{
    public function render()
    {
        return view('livewire.user-index', [
            'users' => User::orderBy('email')->get(),
        ]);
    }

    public function toggleSuperAdmin($userId)
    {
        if ($this->deniesChanges($userId)) {
            abort(403, 'Forbidden');
            return;
        }

        User::findOrFail($userId)->toggleSuperAdmin();
    }

    public function toggleLogIn($userId)
    {
        if ($this->deniesChanges($userId)) {
            abort(403, 'Forbidden');
            return;
        }

        User::findOrFail($userId)->toggleCanLogIn();
    }

    public function deleteUser($userId)
    {
        if ($this->deniesChanges($userId)) {
            abort(403, 'Forbidden');
            return;
        }

        User::findOrFail($userId)->delete();
    }

    public function deniesChanges($userId)
    {
        // only super users can fiddle with things
        if (! auth()->user()->isSuperAdmin()) {
            return true;
        }

        // can't fiddle with yourself o_O
        if (auth()->id() == $userId) {
            return true;
        }

        return false;
    }
}
