<?php

namespace App\Http\Controllers;

use App\Models\Item;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            // 購入日時が新しい順に商品IDを取得
            $purchasedItemIds = DB::table('purchases')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->pluck('item_id');

            // 取得した購入順を維持して商品を並べる
            $items = Item::with('purchase')
                ->whereIn('id', $purchasedItemIds)
                ->get()
                ->sortBy(function ($item) use ($purchasedItemIds) {
                    return $purchasedItemIds->search($item->id);
                })
                ->values();
        } else {
            $items = Item::with('purchase')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    public function edit()
    {
        return view('mypage.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ProfileRequest $request)
    {

        $user = Auth::user();

        $data = $request->only([
            'name',
            'postal_code',
            'address',
            'building',
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update($data);

        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました。');
    }
}