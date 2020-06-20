<?php

namespace App\Http\Livewire;

use App\BackupRestorer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Orkhanahmadov\ZipValidator\Rules\ZipContent;

class DatabaseRestorer extends Component
{
    use WithFileUploads;

    public $restoreFile;

    public function render()
    {
        return view('livewire.database-restorer');
    }

    public function updatedRestoreFile($field)
    {
        $this->validate([
            'restoreFile' => ['required', 'file', 'mimes:zip', new ZipContent('database.sqlite', 'images')],
        ]);
    }

    public function restore()
    {
        $this->validate([
            'restoreFile' => ['required', 'file', 'mimes:zip', new ZipContent('database.sqlite', 'images')],
        ]);

        app(BackupRestorer::class)->restore($this->restoreFile);

        return redirect(route('home'));
    }
}
