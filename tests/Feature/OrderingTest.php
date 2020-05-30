<?php

namespace Tests\Feature;

use App\Item;
use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class OrderingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function customers_can_see_the_details_for_an_item_by_visiting_its_webpage()
    {
        $this->withoutExceptionHandling();
        $item = factory(Item::class)->create([
            'name' => 'A lovely pint',
            'code' => 'R2D2C3PO',
        ]);

        $response = $this->get(route('item.show', $item->code));

        $response->assertOk();
        $response->assertViewHas('item', $item);
        $response->assertSeeLivewire('order-item');
    }

    /** @test */
    public function customers_can_order_an_item()
    {
        $item = factory(Item::class)->create([
            'name' => 'A lovely pint',
            'code' => 'R2D2C3PO',
        ]);

        Livewire::test('order-item', ['item' => $item])
            ->assertSee($item->name)
            ->assertSee($item->description)
            ->assertSee($item->price_in_pounds)
            ->assertSee('Quantity')
            ->assertSee('Contact Details')
            ->assertSee('Place Order')
            ->assertSee('I am 18 or older and agree to pay')
            ->set('quantity', 2)
            ->set('contact', '07123 4567891')
            ->set('confirmPayment', true)
            ->call('placeOrder')
            ->assertDontSee('Place Order')
            ->assertSee("Order for £" . number_format(($item->price * 2) / 100, 2) . " sent");

        tap(Order::first(), function ($order) use ($item) {
            $this->assertEquals('07123 4567891', $order->contact);
            $this->assertEquals(2, $order->quantity);
            $this->assertTrue($order->item->is($item));
        });
    }

    /** @test */
    public function orders_have_to_have_a_valid_quantity_and_contact_info()
    {
        $item = factory(Item::class)->create([
            'name' => 'A lovely pint',
            'code' => 'R2D2C3PO',
        ]);

        Livewire::test('order-item', ['item' => $item])
            ->set('quantity', 'fred')
            ->set('contact', '')
            ->set('confirmPayment', true)
            ->call('placeOrder')
            ->assertDontSee("Order for £" . number_format(($item->price * 2) / 100, 2) . " sent")
            ->assertHasErrors(['contact', 'quantity'])
            ->set('quantity', '1')
            ->set('contact', 'fred@example.com')
            ->call('placeOrder')
            ->assertHasErrors(['contact']);

        $this->assertEquals(0, Order::count());
    }
}
