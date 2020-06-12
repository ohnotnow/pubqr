<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show(string $code)
    {
        $item = Item::where('code', '=', $code)->firstOrFail();

        return view('item.show', [
            'item' => $item,
        ]);
    }

    public function create()
    {
        return view('item.edit', [
            'item' => Item::makeDefault(),
        ]);
    }

    public function edit(Item $item)
    {
        return view('item.edit', [
            'item' => $item,
        ]);
    }
}
