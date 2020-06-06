<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
