<?php

namespace App;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

class QrCodeArchiver
{
    public function create()
    {
        $qrFiles = [];
        $zipFile = tempnam(sys_get_temp_dir(), 'qrcodes');

        $zip = new ZipArchive;
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addEmptyDir('qrcodes');
        Item::all()->each(function ($item) use ($zip, $qrFiles) {
            $fileName = sys_get_temp_dir() . '/qrcode_' . $item->getFilesystemSafeName() . '.png';
            $qrFiles[] = $fileName;
            QrCode::format('png')->size(256)->generate(route('item.show', $item->code), $fileName);
            $zip->addFile($fileName, 'qrcodes/' . basename($fileName));
        });

        $zip->close();

        foreach ($qrFiles as $file) {
            unlink($file);
        }

        return $zipFile;
    }
}
