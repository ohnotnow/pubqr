<?php

namespace App\Http\Livewire;

use App\Item;
use Livewire\Component;

class InventoryList extends Component
{
    public function render()
    {
        return view('livewire.inventory-list', [
            'items' => Item::orderBy('name')->get(),
        ]);
    }

    public function toggleAvailable($itemId): void
    {
        $item = Item::findOrFail($itemId);

        $item->toggleAvailability();
    }
}
