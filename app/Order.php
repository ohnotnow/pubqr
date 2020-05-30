<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['contact', 'quantity', 'item_id'];

    protected $casts = [
        'is_fulfilled' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('is_paid', '=', false);
    }

    public function fulfill()
    {
        $this->is_paid = true;
        $this->is_fulfilled = true;
        $this->save();
    }

    public function getCostInPoundsAttribute()
    {
        return number_format(($this->item->price * $this->quantity) / 100, 2);
    }
}
