<?php

namespace Tests\Feature;

use App\Item;
use App\QrCodeArchiver;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ZipArchive;

class QrCodeDownloadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function we_can_make_a_zip_file_of_all_qr_codes()
    {
        $item = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();

        $path = app(QrCodeArchiver::class)->create();

        $za = new ZipArchive;

        $za->open($path);

        $this->assertEquals('qrcodes', basename($za->statIndex(0)['name']));
        $this->assertStringContainsString($item->getFilesystemSafeName() . '.png', basename($za->statIndex(1)['name']));
        $this->assertStringContainsString($item2->getFilesystemSafeName() . '.png', basename($za->statIndex(2)['name']));

        unlink($path);
    }

    /** @test */
    public function staff_can_download_a_zip_of_all_qrcodes()
    {
        $staff = factory(User::class)->create();
        $item = factory(Item::class)->create();
        $item2 = factory(Item::class)->create();

        $response = $this->actingAs($staff)->get(route('download.qrcodes'));

        $response->assertOk();
    }
}
