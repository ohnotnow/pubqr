<?php

namespace App\Http\Livewire;

use App\Order;
use Livewire\Component;

class OrderItem extends Component
{
    public $item;
    public $quantity = 1;
    public $contact = '';
    public $confirmPayment = false;
    public $orderCost;
    public $saved = false;

    public function mount($item)
    {
        $this->item = $item;
        $this->orderCost = $this->quantity * $this->item->price;
        $this->contact = trim(session('contact_name'));
    }

    public function render()
    {
        return view('livewire.order-item');
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'quantity' => 'required|integer|min:1|max:10',
            'contact' => 'required|not_regex:/\@/',
        ]);
    }

    public function updatedQuantity($value)
    {
        if (! is_numeric($this->quantity)) {
            $this->orderCost = 0;
            return;
        }
        $this->orderCost = $this->quantity * $this->item->price;
    }

    public function placeOrder()
    {
        if (! option('is_open')) {
            abort(403, 'Bar is closed');
        }

        $this->validate([
            'contact' => 'required|not_regex:/\@/',
            'confirmPayment' => 'required:accepted',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $order = Order::create([
            'contact' => $this->contact,
            'quantity' => $this->quantity,
            'item_id' => $this->item->id,
            'cost' => $this->quantity * $this->item->price,
        ]);

        $this->saved = true;
        $this->quantity = 1;
        $this->contact = '';
        $this->confirmPayment = false;
    }
}
