<?php

namespace App;

use Hashids\Hashids;

class CodeGenerator
{
    protected $hasher;

    public function __construct()
    {
        $this->hasher = new Hashids(config('pubqr.hashid_seed', 'sdflwieruqern'), config('pubqr.hashid_length', 6));
    }

    public function generate(int $id): string
    {
        return $this->hasher->encode($id);
    }
}
