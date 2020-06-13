<?php

namespace Tests\Feature\Auth\Passwords;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ResetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_who_have_been_marked_as_force_a_password_reset_are_always_redirected_to_the_reset_page()
    {
        $user = factory(User::class)->create(['force_reset_password' => true]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertRedirect(route('password.reset'));
    }

    /** @test */
    public function can_view_password_reset_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('password.reset'));

        $response->assertSuccessful();
        $response->assertSeeLivewire('auth.passwords.reset');
    }

    /** @test */
    public function can_reset_password()
    {
        $user = factory(User::class)->create(['force_reset_password' => true]);
        $originalPasswordHash = $user->password;

        Livewire::actingAs($user)->test('auth.passwords.reset')
            ->set('password', 'new-password-123')
            ->set('passwordConfirmation', 'new-password-123')
            ->call('resetPassword')
            ->assertRedirect(route('home'));

        $this->assertFalse($user->fresh()->force_reset_password);
        $this->assertNotEquals($originalPasswordHash, $user->fresh()->password);
    }

    /** @test */
    function password_is_required()
    {
        $user = factory(User::class)->create(['force_reset_password' => true]);

        Livewire::actingAs($user)->test('auth.passwords.reset')
            ->set('password', '')
            ->set('passwordConfirmation', '')
            ->call('resetPassword')
            ->assertHasErrors('password');
    }

    /** @test */
    function password_is_minimum_of_twelve_characters()
    {
        $user = factory(User::class)->create(['force_reset_password' => true]);

        Livewire::actingAs($user)->test('auth.passwords.reset')
            ->set('password', 'abcdefghijk')
            ->set('passwordConfirmation', 'abcdefghijk')
            ->call('resetPassword')
            ->assertHasErrors('password');
    }

    /** @test */
    function password_matches_password_confirmation()
    {
        $user = factory(User::class)->create(['force_reset_password' => true]);

        Livewire::actingAs($user)->test('auth.passwords.reset')
            ->set('password', 'abcdefghijklm')
            ->set('passwordConfirmation', 'abcdefghijkLM')
            ->call('resetPassword')
            ->assertHasErrors(['password' => 'same']);
    }

    /** @test */
    function newpassword_must_be_different_to_the_old_password()
    {
        $user = factory(User::class)->create(['force_reset_password' => true, 'password' => bcrypt('myamazingpassword')]);

        Livewire::actingAs($user)->test('auth.passwords.reset')
            ->set('password', 'myamazingpassword')
            ->set('passwordConfirmation', 'myamazingpassword')
            ->call('resetPassword')
            ->assertHasErrors('password');
    }
}
