<?php

namespace App\Http\Livewire;

use App\CodeGenerator;
use App\Item;
use Livewire\Component;

class ItemEditor extends Component
{
    public $item;

    public $confirmDelete = false;

    public $deleteButtonText = "Delete Item";

    public function mount($item)
    {
        $this->item = $item;
    }

    public function render()
    {
        return view('livewire.item-editor');
    }

    public function saveItem()
    {
        $this->validate([
            'item.name' => 'required',
            'item.description' => 'sometimes|max:1024',
            'item.price' => 'required|numeric|min:1',
        ]);

        if (isset($this->item['id'])) {
            $item = Item::findOrFail($this->item['id']);
            $item->update([
                'name' => $this->item['name'],
                'description' => $this->item['description'],
                'price' => $this->item['price'] * 100,
            ]);
        } else {
            $item = Item::create([
                'name' => $this->item['name'],
                'description' => $this->item['description'],
                'price' => $this->item['price'] * 100,
            ]);
            $item->code = app(CodeGenerator::class)->generate($item->id);
            $item->save();
        }

        return redirect(route('inventory.index'));
    }

    public function deleteItem()
    {
        // trap attempting to delete while in 'create a new item' mode
        if (! isset($this->item['id'])) {
            return;
        }

        if (! $this->confirmDelete) {
            $this->confirmDelete = true;
            $this->deleteButtonText = 'Confirm Delete Item';
            return;
        }

        $item = Item::findOrFail($this->item['id']);
        $item->delete();

        return redirect(route('inventory.index'));
    }
}
