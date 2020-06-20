<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupGenerator
{
    public function backup()
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'backup');

        $zip = new ZipArchive;
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addEmptyDir('images');
        Item::all()->filter(function ($item) {
            return $item->image;
        })->each(function ($item) use ($zip) {
            $zip->addFile(Storage::disk('images')->path($item->image), 'images/' . $item->image);
        });

        $zip->addFile(config('database.connections.sqlite.database'), 'database.sqlite');
        $zip->close();

        return $zipFile;
    }
}
