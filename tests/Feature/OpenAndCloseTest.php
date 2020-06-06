<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class OpenAndCloseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_can_see_the_open_close_toggle()
    {
        $staff = factory(User::class)->create();

        $response = $this->actingAs($staff)->get(route('home'));

        $response->assertOk();
        $response->assertSeeLivewire('open-close-toggle');
    }

    /** @test */
    public function staff_can_toggle_the_open_closed_state_of_the_app()
    {
        $staff = factory(User::class)->create();

        Livewire::actingAs($staff)
            ->test('open-close-toggle')
            ->assertDontSee('Close the bar')
            ->assertSee('Open the bar')
            ->call('toggleOpen')
            ->assertSee('Close the bar')
            ->assertDontSee('Open the bar')
            ->call('toggleOpen')
            ->assertDontSee('Close the bar')
            ->assertSee('Open the bar');
    }

    /** @test */
    public function toggling_the_open_closed_state_of_the_app_changes_the_value_of_the_option_in_the_db()
    {
        $staff = factory(User::class)->create();

        $this->assertNull(option('is_open'));

        Livewire::actingAs($staff)
            ->test('open-close-toggle')
            ->call('toggleOpen');

        $this->assertTrue(option('is_open'));

        Livewire::actingAs($staff)
            ->test('open-close-toggle')
            ->call('toggleOpen');

        $this->assertFalse(option('is_open'));
    }
}
