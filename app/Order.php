<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['contact', 'quantity', 'item_id'];

    protected $casts = [
        'is_fulfilled' => 'boolean',
        'is_cancelled' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function fulfiller()
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }

    public function scopeIncomplete($query)
    {
        return $query->where('is_paid', '=', false)->where('is_cancelled', '!=', true);
    }

    public function fulfill()
    {
        $this->is_paid = true;
        $this->is_fulfilled = true;
        $this->fulfilled_by = auth()->id();
        $this->save();
    }

    public function cancel()
    {
        $this->is_cancelled = true;
        $this->fulfilled_by = auth()->id();
        $this->save();
    }

    public function getCostInPoundsAttribute()
    {
        return number_format(($this->item->price * $this->quantity) / 100, 2);
    }

    public function getStatus()
    {
        if ($this->is_fulfilled) {
            return 'Complete';
        }

        if ($this->is_cancelled) {
            return 'Cancelled';
        }

        return 'Pending';
    }
}
