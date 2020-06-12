<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Item extends Model
{
    protected $fillable = ['name', 'description', 'price', 'code', 'image'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    protected $attributes = [
        'code' => '',
        'name' => '',
        'description' => '',
        'price' => 0,
        'is_available' => true,
        'image' => null,
    ];

    public static function makeDefault()
    {
        return new static([
            'code' => '',
            'name' => '',
            'description' => '',
            'price' => 0,
            'is_available' => true,
            'image' => '',
        ]);
    }

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

    public function updateImage(UploadedFile $image)
    {
        $filename = $image->store('', 'images');
        $this->image = $filename;
        $this->save();
    }

    public function getImageUrlAttribute()
    {
        if (isset($this->image)) {
            return asset('images/' . $this->image);
        }

        return 'https://dummyimage.com/400x400';
    }
}
