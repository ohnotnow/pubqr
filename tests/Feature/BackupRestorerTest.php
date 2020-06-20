<?php

namespace Tests\Feature;

use App\BackupGenerator;
use App\BackupRestorer;
use App\Item;
use App\Order;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupRestorerTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function we_can_restore_the_db_and_images_from_a_generated_backup()
    {
        Storage::fake('images');
        // we create a new temp sqlite file on disk so we can back up it rather than using :memory:
        $newDb = tempnam(sys_get_temp_dir(), 'testdb');
        config(['database.connections.sqlite.database' => $newDb]);
        $this->artisan('migrate:fresh');

        $superadmin = factory(User::class)->states('superadmin')->create();
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $order1 = factory(Order::class)->create();
        $order2 = factory(Order::class)->create();
        $fakeImage1 = UploadedFile::fake()->image('first.jpg');
        $fakeImage2 = UploadedFile::fake()->image('second.jpg');
        $item1 = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();
        $item1->updateImage($fakeImage1);
        $item2->updateImage($fakeImage2);

        $this->assertEquals(3, User::count());
        $this->assertEquals(2, Order::count());
        $this->assertEquals(4, Item::count());
        $this->assertCount(2, Storage::disk('images')->files());

        $backupFile = app(BackupGenerator::class)->backup();

        $this->artisan('migrate:fresh');
        Storage::fake('images');

        $this->assertEquals(0, User::count());
        $this->assertEquals(0, Order::count());
        $this->assertEquals(0, Item::count());
        $this->assertCount(0, Storage::disk('images')->files());

        app(BackupRestorer::class)->restore($backupFile);

        $this->assertEquals(3, User::count());
        $this->assertEquals(2, Order::count());
        $this->assertEquals(4, Item::count());
        $this->assertCount(2, Storage::disk('images')->files());

        unlink($newDb);
    }
}
