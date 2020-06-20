<?php

namespace Tests\Feature;

use App\BackupRestorer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class RestoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function superadmins_can_see_the_restore_button()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();

        $response = $this->actingAs($superAdmin)->get(route('home'));

        $response->assertSee('database-restorer');
    }

    /** @test */
    public function regular_users_dont_see_the_restore_button()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertDontSee('database-restorer');
    }

    /** @test */
    public function superadmins_can_restore_a_db_backup()
    {
        $superAdmin = factory(User::class)->states('superadmin')->create();
        $fakeDbBackup = UploadedFile::fake()->create('backup.zip', 1);
        $this->mock(BackupRestorer::class, function ($mock) {
            $mock->shouldReceive('restore')->once();
        });

        Livewire::actingAs($superAdmin)
            ->test('database-restorer')
            ->set('restoreFile', $fakeDbBackup)
            ->call('restore')
            ->assertHasNoErrors()
            ->assertRedirect(route('home'));
    }
}
