<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Customer;
use Livewire\Livewire;

class ContactDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function customers_who_havent_logged_a_contact_number_must_provide_one()
    {
        $this->withoutExceptionHandling();

        session()->forget('contact_number');
        session()->forget('contact_name');

        $response = $this->get(route('customer.home'));

        $response->assertRedirect(route('contact-number.create'));
    }

    /** @test */
    public function customers_who_have_logged_a_contact_number_can_use_the_site()
    {
        $this->withoutExceptionHandling();

        session()->put('contact_number', '0123456789');
        session()->put('contact_name', 'Jenny');

        $response = $this->get(route('customer.home'));

        $response->assertOk();
    }

    /** @test */
    public function customers_can_log_a_contact_number()
    {
        $this->withoutExceptionHandling();
        session()->forget('contact_number');
        session()->forget('contact_name');

        $response = $this->get(route('contact-number.create'));

        $response->assertOk();

        $response = $this->post(route('contact-number.store'), [
            'contact_number' => '0123456789',
            'contact_name' => 'Hello Kitty',
        ]);

        $response->assertRedirect(route('customer.home'));
        $response->assertSessionHas('contact_number', '0123456789');
        $response->assertSessionHas('contact_name', 'Hello Kitty');
        tap(Customer::first(), function ($customer) {
            $this->assertEquals('0123456789', decrypt($customer->contact_number));
            $this->assertEquals('Hello Kitty', decrypt($customer->contact_name));
        });
    }

    /** @test */
    public function superadmins_can_see_the_list_of_all_customers_contact_details()
    {
        $this->withoutExceptionHandling();
        $staff = factory(User::class)->create(['is_superadmin' => true]);
        $customer1 = factory(Customer::class)->create(['contact_number' => encrypt('0123456789'), 'contact_name' => encrypt('Jenny Smith')]);
        $customer2 = factory(Customer::class)->create(['contact_number' => encrypt('1234567890'), 'contact_name' => encrypt('Jimmy Crankie')]);

        $response = $this->actingAs($staff)->get(route('customer.index'));

        $response->assertOk();
        $response->assertSee('0123456789');
        $response->assertSee('Jenny Smith');
        $response->assertSee($customer1->created_at->format('d/m/Y H:i'));
        $response->assertSee('1234567890');
        $response->assertSee('Jimmy Crankie');
        $response->assertSee($customer2->created_at->format('d/m/Y H:i'));
    }

    /** @test */
    public function non_superadmins_cant_see_the_list_of_all_customers_contact_details()
    {
        $staff = factory(User::class)->create(['is_superadmin' => false]);

        $response = $this->actingAs($staff)->get(route('customer.index'));

        $response->assertForbidden();
    }

    /** @test */
    public function superadmins_can_filter_the_list_of_contact_details_to_a_specific_date()
    {
        $this->withoutExceptionHandling();
        $staff = factory(User::class)->create(['is_superadmin' => true]);
        $customer1 = factory(Customer::class)->create(['created_at' => now()->subDays(1), 'contact_number' => encrypt('0123456789'), 'contact_name' => encrypt('Jenny Smith')]);
        $customer2 = factory(Customer::class)->create(['created_at' => now()->subDays(3), 'contact_number' => encrypt('1234567890'), 'contact_name' => encrypt('Jimmy Crankie')]);

        Livewire::actingAs($staff)
            ->test('customer-index')
            ->assertSee(decrypt($customer1->contact_name))
            ->assertSee(decrypt($customer2->contact_name))
            ->set('date', now()->subDays(1)->format('d/m/Y'))
            ->assertSee(decrypt($customer1->contact_name))
            ->assertDontSee(decrypt($customer2->contact_name))
            ->set('date', now()->subDays(3)->format('d/m/Y'))
            ->assertDontSee(decrypt($customer1->contact_name))
            ->assertSee(decrypt($customer2->contact_name))
            ->set('date', '')
            ->assertSee(decrypt($customer1->contact_name))
            ->assertSee(decrypt($customer2->contact_name));
    }

    /** @test */
    public function customer_details_are_removed_after_some_time()
    {
        config(['pubqr.retain_customer_details_days' => 3]);
        $customer1 = factory(Customer::class)->create(['created_at' => now()->subDays(1)]);
        $customer2 = factory(Customer::class)->create(['created_at' => now()->subDays(4)]);

        $this->artisan('pubqr:purge-old-customer-records');

        $this->assertDatabaseMissing('customers', ['id' => $customer2->id]);
        $this->assertDatabaseHas('customers', ['id' => $customer1->id]);
    }

    /** @test */
    public function the_artisan_command_to_purge_the_records_is_registered()
    {
        $this->assertCommandIsScheduled('pubqr:purge-old-customer-records');
    }
}
