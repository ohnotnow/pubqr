<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupRestorer
{
    /**
     *
     * @param string|UploadedFile $file
     * @return void
     */
    public function restore($file)
    {
        if ($file instanceof UploadedFile) {
            $file = $file->getPathname();
        }

        $zip = new ZipArchive;
        $zip->open($file);
        file_put_contents(config('database.connections.sqlite.database'), $zip->getFromName('database.sqlite'));
        for($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (! preg_match('/images.+[a-zA-Z0-9]/', $filename)) {
                continue;
            }
            $contents = $zip->getFromIndex($i);
            Storage::disk('images')->put(basename($filename), $contents);
        }
        $zip->close();
    }
}
