<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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

    $alreadyPurchased = DB::table('purchases')
        ->where('item_id', $item_id)
        ->exists();

    if ($alreadyPurchased) {
        return redirect()->route('items.show', ['item_id' => $item->id]);
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    $checkoutSession = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $item->name,
                ],
                'unit_amount' => $item->price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('purchase.success', [
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
        ], true),
        'cancel_url' => route('purchase.cancel', [
            'item_id' => $item->id,
        ], true),
    ]);

    return redirect($checkoutSession->url);
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

    public function success(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $alreadyPurchased = DB::table('purchases')
            ->where('item_id', $item_id)
            ->exists();

        if (!$alreadyPurchased) {
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
    }

    return redirect()->route('items.index');
}

    public function cancel($item_id)
    {
    return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}