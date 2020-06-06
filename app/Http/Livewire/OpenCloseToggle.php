<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OpenCloseToggle extends Component
{
    public $open;

    public $buttonText;

    public function mount()
    {
        $this->open = option('is_open', false);
        $this->buttonText = $this->open ? 'Close the bar' : 'Open the bar';
    }

    public function render()
    {
        return view('livewire.open-close-toggle');
    }

    public function toggleOpen()
    {
        option(['is_open' => ! option('is_open', false)]);
        $this->open = option('is_open');
        $this->buttonText = $this->open ? 'Close the bar' : 'Open the bar';
    }
}
