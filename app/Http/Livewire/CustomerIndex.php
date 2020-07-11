<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Customer;
use Carbon\Carbon;

class CustomerIndex extends Component
{
    public $customers;

    public $date = null;

    public function mount()
    {
        $this->customers = Customer::orderByDesc('created_at')->get();
    }

    public function render()
    {
        return view('livewire.customer-index');
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'date' => 'sometimes|date_format:d/m/Y',
        ]);
    }

    public function updatedDate($newDate)
    {
        if (! $newDate) {
            $this->customers = Customer::orderByDesc('created_at')->get();
            return;
        }

        $carbonDate = Carbon::createFromFormat('d/m/Y', $newDate);
        $this->customers = Customer::orderByDesc('created_at')->whereDate('created_at', $carbonDate->format('Y-m-d'))->get();
    }
}
