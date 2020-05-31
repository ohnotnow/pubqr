<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_can_see_the_inventory_page()
    {
        $staff = factory(User::class)->create();
        $item1 = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();

        $response = $this->actingAs($staff)->get(route('inventory.index'));

        $response->assertOk();
        $response->assertSeeLivewire('inventory-list');
    }

    /** @test */
    public function non_staff_cant_see_the_inventory_page()
    {
        $response = $this->get(route('inventory.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function staff_can_see_the_current_inventory_items()
    {
        $staff = factory(User::class)->create();
        $item1 = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();

        Livewire::actingAs($staff)->test('inventory-list')
            ->assertSee($item1->name)
            ->assertSee($item2->name);
    }

    /** @test */
    public function staff_can_mark_an_item_as_available_or_not()
    {
        $staff = factory(User::class)->create();
        $item1 = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();

        $this->assertTrue($item2->isAvailable());

        Livewire::actingAs($staff)->test('inventory-list')
            ->assertSee($item1->name)
            ->assertSee($item2->name)
            ->call('toggleAvailable', $item2->id);

        $this->assertFalse($item2->fresh()->isAvailable());

        Livewire::actingAs($staff)->test('inventory-list')
            ->assertSee($item1->name)
            ->assertSee($item2->name)
            ->call('toggleAvailable', $item2->id);

        $this->assertTrue($item2->fresh()->isAvailable());
    }

    /** @test */
    public function staff_can_add_a_new_item()
    {
        $staff = factory(User::class)->create();

        Livewire::actingAs($staff)->test('item-editor', ['item' => (new Item)->toArray()])
            ->assertSee('Add a new item')
            ->set('item.name', 'Porter 2')
            ->set('item.description', 'A rich chocolate porter')
            ->set('item.price', '3.84')
            ->call('saveItem')
            ->assertRedirect(route('inventory.index'));

        $item = Item::first();
        $this->assertEquals('Porter 2', $item->name);
        $this->assertEquals('A rich chocolate porter', $item->description);
        $this->assertEquals(384, $item->price);
        $this->assertNotNull($item->code);
    }

    /** @test */
    public function staff_can_edit_an_existing_item()
    {
        $staff = factory(User::class)->create();
        $item = factory(Item::class)->create();

        Livewire::actingAs($staff)->test('item-editor', ['item' => $item->toArray()])
            ->assertSee('Edit Item')
            ->set('item.name', 'Porter 2')
            ->set('item.description', 'A rich chocolate porter')
            ->set('item.price', '3.84')
            ->call('saveItem')
            ->assertRedirect(route('inventory.index'));

        $updatedItem = Item::first();
        $this->assertEquals('Porter 2', $updatedItem->name);
        $this->assertEquals('A rich chocolate porter', $updatedItem->description);
        $this->assertEquals(384, $updatedItem->price);
        $this->assertEquals($item->code, $updatedItem->code);
    }

    /** @test */
    public function staff_can_delete_an_existing_item()
    {
        $staff = factory(User::class)->create();
        $item = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();

        $this->assertEquals(2, Item::count());

        Livewire::actingAs($staff)->test('item-editor', ['item' => $item->toArray()])
            ->assertSee('Edit Item')
            ->assertSee('Delete Item')
            ->call('deleteItem')
            ->assertSee('Confirm Delete Item')
            ->call('deleteItem')
            ->assertRedirect(route('inventory.index'));

        $this->assertEquals(1, Item::count());
        $this->assertDatabaseHas('items', ['id' => $item2->id]);
    }
}
