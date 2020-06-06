<?php

namespace App\Http\Livewire;

use App\Order;
use Livewire\Component;

class OrdersPage extends Component
{
    public function render()
    {
        return view('livewire.orders-page', [
            'orders' => Order::with('item')->incomplete()->groupBy('contact')->orderBy('created_at')->get(),
        ]);
    }

    public function fulfill($orderId)
    {
        $order = Order::find($orderId);
        if (! $order) {
            $this->error = "Could not find order {$orderId}";
            return;
        }

        $order->fulfill();
    }

    public function cancel($orderId)
    {
        $order = Order::find($orderId);
        if (! $order) {
            $this->error = "Could not find order {$orderId}";
            return;
        }

        $order->cancel();
    }
}
