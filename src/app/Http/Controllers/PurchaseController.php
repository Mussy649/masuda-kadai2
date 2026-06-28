<?php

namespace App\Http\Controllers;

use App\Models\Item;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchases.show', compact('item'));
    }
}