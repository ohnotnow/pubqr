<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Customer;
use Carbon\Carbon;

class CustomerIndex extends Component
{
    use WithPagination;

    public $date = null;

    public function render()
    {
        return view('livewire.customer-index', [
            'customers' => $this->getCustomers(),
        ]);
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'date' => 'sometimes|date_format:d/m/Y',
        ]);
    }

    public function updatedDate($newDate)
    {
        $this->resetPage(); 
    }

    public function getCustomers()
    {
        if (! $this->date) {
            return Customer::orderByDesc('created_at')->paginate(100);
        }

        $carbonDate = Carbon::createFromFormat('d/m/Y', $this->date);
        return Customer::orderByDesc('created_at')->whereDate('created_at', $carbonDate->format('Y-m-d'))->paginate(100);
    }
}
