<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function getPriceInPoundsAttribute(): string
    {
        return number_format($this->price / 100, 2);
    }
}
