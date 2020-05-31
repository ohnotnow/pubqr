<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    protected $fillable = ['name', 'description', 'price', 'code'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function getPriceInPoundsAttribute(): string
    {
        return number_format($this->price / 100, 2);
    }

    public function isAvailable()
    {
        return $this->is_available;
    }

    public function toggleAvailability()
    {
        $this->is_available = ! $this->is_available;
        $this->save();
    }

    public function getFilesystemSafeName()
    {
        return Str::slug($this->name);
    }
}
