<?php

namespace App\Http\Controllers;


use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
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

        if ((int) $item->user_id === (int) Auth::id()) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $alreadyPurchased = DB::table('purchases')
            ->where('item_id', $item_id)
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $user = Auth::user();

            return view('purchases.show', compact('item', 'user'));
    }
    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ((int) $item->user_id === (int) Auth::id()) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $alreadyPurchased = DB::table('purchases')
            ->where('item_id', $item_id)
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $validated = $request->validated();
        $paymentMethod = $validated['payment_method'];
        $stripePaymentMethod = $paymentMethod === 'コンビニ払い'
            ? 'konbini'
            : 'card';

    $user = Auth::user();

    Stripe::setApiKey(config('services.stripe.secret'));

    $checkoutSession = Session::create([
        'payment_method_types' => [$stripePaymentMethod],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ],
        ],
        'mode' => 'payment',
        'success_url' => route('purchase.success', [
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
        ], true),
        'cancel_url' => route('purchase.cancel', [
            'item_id' => $item->id,
        ], true),
    ]);

    if ($paymentMethod === 'コンビニ払い') {
        $this->createPurchaseIfNotExists(
            $item,
            $user,
            $paymentMethod
        );
    }

    return redirect($checkoutSession->url);
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ((int) $item->user_id === (int) Auth::id()) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $alreadyPurchased = DB::table('purchases')
            ->where('item_id', $item_id)
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $user = Auth::user();

        return view('purchases.address', compact('item', 'user'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ((int) $item->user_id === (int) Auth::id()) {
        return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $alreadyPurchased = DB::table('purchases')
        ->where('item_id', $item_id)
        ->exists();

        if ($alreadyPurchased) {
        return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $validated = $request->validated();

        $user = Auth::user();

        $user->update([
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    public function success(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        if ((int) $item->user_id === (int) Auth::id()) {
        return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $paymentMethod = $request->input('payment_method');

        if (!$paymentMethod) {
        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('message', '支払い方法を選択してください。');
        }

        $this->createPurchaseIfNotExists(
        $item,
        $user,
        $paymentMethod
        );

        return redirect()->route('mypage.index', ['page' => 'buy']);
    }

    private function createPurchaseIfNotExists(
        Item $item,
        $user,
        string $paymentMethod
        ): void {
        $alreadyPurchased = DB::table('purchases')
        ->where('item_id', $item->id)
        ->exists();

        if ($alreadyPurchased) {
        return;
        }

        DB::table('purchases')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function cancel($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ((int) $item->user_id === (int) Auth::id()) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $alreadyPurchased = DB::table('purchases')
            ->where('item_id', $item_id)
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}