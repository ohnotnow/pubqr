<?php

namespace Tests\Feature;

use App\BackupGenerator;
use App\Item;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class BackupTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function we_can_generate_a_backup_of_the_database_and_images()
    {
        Storage::fake('images');
        $items = factory(Item::class, 5)->create();
        $items->each->updateImage(UploadedFile::fake()->image($this->faker->word . $this->faker->fileExtension));

        $filename = app(BackupGenerator::class)->backup();

        $this->assertNotNull($filename);

        $za = new ZipArchive;
        $za->open($filename);
        $this->assertEquals('images', basename($za->statIndex(0)['name']));
        Item::all()->each(function ($item) use ($za) {
            $this->assertNotFalse($za->locateName('images/' . $item->image));
        });
        $this->assertNotFalse($za->locateName('database.sqlite'));
        unlink($filename);
    }

    /** @test */
    public function superadmins_can_download_a_backup_of_the_site()
    {
        $admin = factory(User::class)->states('superadmin')->create();

        $response = $this->actingAs($admin)->get(route('download.backup'));

        $response->assertOk();
        $response->assertHeader('Content-Disposition');
    }

    /** @test */
    public function non_superadminst_can_download_a_backup_of_the_site()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('download.backup'));

        $response->assertForbidden();

        $response = $this->get(route('download.backup'));

        $response->assertForbidden();
    }
}
