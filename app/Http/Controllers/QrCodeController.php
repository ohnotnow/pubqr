<?php

namespace App\Http\Controllers;

use App\QrCodeArchiver;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function show()
    {
        $zip = (new QrCodeArchiver)->create();

        return response()->download($zip);
    }
}
