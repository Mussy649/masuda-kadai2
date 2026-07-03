<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('purchases.show', compact('item', 'user'));
    }

    public function store(Request $request, $item_id)
    {
        $request->validate([
        'payment_method' => ['required'],
        ], [
        'payment_method.required' => '支払い方法を選択してください。',
        ]);

        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $alreadyPurchased = DB::table('purchases')
            ->where('item_id', $item->id)
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        DB::table('purchases')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('items.index');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('purchases.address', compact('item', 'user'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

    return redirect()->route('purchase.show', ['item_id' => $item_id]);

    }
}