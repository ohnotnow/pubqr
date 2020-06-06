<?php

namespace Tests\Feature;

use App\Order;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FullfillmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_can_see_the_list_of_pending_orders()
    {
        $this->withoutExceptionHandling();
        $staff = factory(User::class)->create();
        $order1 = factory(Order::class)->create();
        $order2 = factory(Order::class)->create();

        $response = $this->actingAs($staff)->get(route('order.index'));

        $response->assertOk();
        $response->assertSeeLivewire('orders-page');
    }

    /** @test */
    public function unauthenticated_users_cant_see_the_list_of_pending_orders()
    {
        $response = $this->get(route('order.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function staff_can_mark_orders_as_fullfilled()
    {
        $this->withoutExceptionHandling();
        $staff = factory(User::class)->create();
        $order1 = factory(Order::class)->create();
        $order2 = factory(Order::class)->create();

        Livewire::actingAs($staff)->test('orders-page')
            ->assertSee($order1->item->name)
            ->assertSee($order2->item->name)
            ->call('fulfill', $order1->id)
            ->assertDontSee($order1->item->name);

        $this->assertTrue($order1->fresh()->is_fulfilled);
        $this->assertTrue($order1->fresh()->fulfiller->is($staff));
    }

    /** @test */
    public function staff_can_mark_orders_as_cancelled()
    {
        $this->withoutExceptionHandling();
        $staff = factory(User::class)->create();
        $order1 = factory(Order::class)->create();
        $order2 = factory(Order::class)->create();

        Livewire::actingAs($staff)->test('orders-page')
            ->assertSee($order1->item->name)
            ->assertSee($order2->item->name)
            ->call('cancel', $order1->id)
            ->assertDontSee($order1->item->name);

        $this->assertTrue($order1->fresh()->is_cancelled);
        $this->assertTrue($order1->fresh()->fulfiller->is($staff));
    }

    /** @test */
    public function admin_staff_can_see_the_order_history_page()
    {
        $this->withoutExceptionHandling();
        $admin = factory(User::class)->create();
        $order1 = factory(Order::class)->create();
        $order2 = factory(Order::class)->create();
        $order2->fulfill();

        $response = $this->actingAs($admin)->get(route('order.history'));

        $response->assertOk();
        $response->assertSee($order1->id);
        $response->assertSee($order1->item->name);
        $response->assertSee($order2->id);
        $response->assertSee($order2->item->name);
    }
}
