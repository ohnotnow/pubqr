<?php

namespace Tests\Feature;

use App\Mail\ResetYourPasswordMail;
use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_can_see_the_user_management_page()
    {
        $staff = factory(User::class)->create();

        $response = $this->actingAs($staff)->get(route('user.index'));

        $response->assertOk();
        $response->assertSeeLivewire('user-index');
    }

    /** @test */
    public function non_staff_cant_see_the_user_management_page()
    {
        $response = $this->get(route('user.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function superadmins_can_toggle_settings_on_user_accounts()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();
        $staff = factory(User::class)->create();

        $this->assertFalse($staff->isSuperAdmin());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($staff->email)
            ->call('toggleSuperAdmin', $staff->id);

        $this->assertTrue($staff->fresh()->isSuperAdmin());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($staff->email)
            ->call('toggleSuperAdmin', $staff->id);

        $this->assertFalse($staff->fresh()->isSuperAdmin());

        $this->assertTrue($staff->canLogIn());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($staff->email)
            ->call('toggleLogIn', $staff->id);

        $this->assertFalse($staff->fresh()->canLogIn());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($staff->email)
            ->call('toggleLogIn', $staff->id);

        $this->assertTrue($staff->fresh()->canLogIn());
    }

    /** @test */
    public function regular_staff_cant_toggle_settings_on_user_accounts()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();
        $staff = factory(User::class)->create();
        $otherStaff = factory(User::class)->create();

        $this->assertFalse($otherStaff->isSuperAdmin());

        Livewire::actingAs($staff)
            ->test('user-index')
            ->assertSee($otherStaff->email)
            ->call('toggleSuperAdmin', $otherStaff->id)
            ->assertForbidden();

        $this->assertFalse($otherStaff->fresh()->isSuperAdmin());

        $this->assertTrue($otherStaff->canLogIn());

        Livewire::actingAs($staff)
            ->test('user-index')
            ->assertSee($otherStaff->email)
            ->call('toggleLogIn', $otherStaff->id)
            ->assertForbidden();

        $this->assertTrue($otherStaff->fresh()->canLogIn());
    }

    /** @test */
    public function superadmins_cant_disable_themselves()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();

        $this->assertTrue($superAdmin->isSuperAdmin());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($superAdmin->email)
            ->call('toggleSuperAdmin', $superAdmin->id)
            ->assertForbidden();

        $this->assertTrue($superAdmin->fresh()->isSuperAdmin());

        $this->assertTrue($superAdmin->canLogIn());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($superAdmin->email)
            ->call('toggleLogIn', $superAdmin->id)
            ->assertForbidden();

        $this->assertTrue($superAdmin->fresh()->canLogIn());
    }

    /** @test */
    public function superadmins_can_delete_users()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();
        $staff = factory(User::class)->create();
        $otherStaff = factory(User::class)->create();

        $this->assertEquals(3, User::count());

        Livewire::actingAs($superAdmin)
            ->test('user-index')
            ->assertSee($otherStaff->email)
            ->call('deleteUser', $otherStaff->id)
            ->assertDontSee($otherStaff->email);

        $this->assertEquals(2, User::count());
        $this->assertDatabaseMissing('users', ['id' => $otherStaff->id]);
    }

    /** @test */
    public function super_admins_can_create_new_users()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();

        Livewire::actingAs($superAdmin)
            ->test('user-editor', ['user' => (new User)->toArray()])
            ->assertSee('Add a New User')
            ->set('user.name', 'fred')
            ->set('user.email', 'fred@example.com')
            ->call('save')
            ->assertRedirect(route('user.index'));

        $user = User::where('name', '=', 'fred')->firstOrFail();
        $this->assertEquals('fred@example.com', $user->email);
        $this->assertNotNull($user->password);
        $this->assertTrue($user->canLogIn());
    }

    /** @test */
    public function super_admins_can_edit_existing_users()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();
        $user = factory(User::class)->create();

        Livewire::actingAs($superAdmin)
            ->test('user-editor', ['user' => $user->toArray()])
            ->assertSee('Edit User')
            ->set('user.name', 'fred')
            ->set('user.email', 'fred@example.com')
            ->call('save')
            ->assertRedirect(route('user.index'));

        $user = User::where('name', '=', 'fred')->firstOrFail();
        $this->assertEquals('fred@example.com', $user->email);
        $this->assertNotNull($user->password);
        $this->assertTrue($user->canLogIn());
    }

    /** @test */
    public function super_admins_can_force_a_user_to_reset_their_password()
    {
        Notification::fake();
        $superAdmin = factory(User::class)->states('superadmin')->create();
        $user = factory(User::class)->create();

        Livewire::actingAs($superAdmin)
            ->test('user-editor', ['user' => $user->toArray()])
            ->assertSee('Edit User')
            ->set('reset_password', true)
            ->call('save')
            ->assertRedirect(route('user.index'));

        $this->assertTrue($user->fresh()->force_reset_password);
    }
}
