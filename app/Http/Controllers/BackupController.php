<?php

namespace App\Http\Controllers;

use App\BackupGenerator;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public $generator;

    public function __construct(BackupGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function show()
    {
        if (auth()->guest()) {
            abort(403, 'Forbidden');
        }
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Forbidden');
        }

        $zip = $this->generator->backup();

        return response()->download($zip);
    }
}
