<?php

namespace App\Http\Livewire;

use App\CodeGenerator;
use App\Item;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemEditor extends Component
{
    use WithFileUploads;

    public $item;

    public $editingExistingItem = false;

    public $confirmDelete = false;

    public $deleteButtonText = "Delete Item";

    public $price_in_pounds;

    public $newImage;

    public function mount($item)
    {
        $this->item = $item;
        $this->editingExistingItem = isset($item['id']);
        $this->price_in_pounds = number_format(($item['price'] ?: 0) / 100, 2);
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
            'price_in_pounds' => 'required|numeric|min:1',
            'newImage' => 'nullable|image|max:1024',
        ]);

        if ($this->editingExistingItem) {
            $item = Item::findOrFail($this->item['id']);
            $item->update([
                'name' => $this->item['name'],
                'description' => $this->item['description'],
                'price' => $this->price_in_pounds * 100,
            ]);
            if ($this->newImage) {
                $item->updateImage($this->newImage);
            }
            return redirect(route('inventory.index'));
        }

        $item = Item::create([
            'name' => $this->item['name'],
            'description' => $this->item['description'],
            'price' => $this->price_in_pounds * 100,
        ]);
        $item->code = app(CodeGenerator::class)->generate($item->id);
        $item->save();

        if ($this->newImage) {
            $item->updateImage($this->newImage);
        }

        return redirect(route('inventory.index'));
    }

    public function deleteItem()
    {
        if (! $this->editingExistingItem) {
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

    public function getImageUrl()
    {
        if ($this->newImage) {
            return $this->newImage->temporaryUrl();
        }

        if (isset($this->item['image'])) {
            return asset('images/' . $this->item['image']);
        }

        return 'https://dummyimage.com/400x400';
    }
}
