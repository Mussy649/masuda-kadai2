<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function update(Request $request)
    {
        $request->validate(
            [
                'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'name' => ['required', 'string', 'max:255'],
                'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
                'address' => ['required', 'string', 'max:255'],
                'building' => ['nullable', 'string', 'max:255'],
            ],
            [
                'profile_image.image' => 'プロフィール画像は画像ファイルを選択してください。',
                'profile_image.mimes' => 'プロフィール画像はjpeg、png、jpg形式で選択してください。',
                'profile_image.max' => 'プロフィール画像は2MB以内で選択してください。',

                'name.required' => 'ユーザー名を入力してください。',
                'name.string' => 'ユーザー名は文字列で入力してください。',
                'name.max' => 'ユーザー名は255文字以内で入力してください。',

                'postal_code.required' => '郵便番号を入力してください。',
                'postal_code.regex' => '郵便番号は123-4567の形式で入力してください。',

                'address.required' => '住所を入力してください。',
                'address.string' => '住所は文字列で入力してください。',
                'address.max' => '住所は255文字以内で入力してください。',

                'building.string' => '建物名は文字列で入力してください。',
                'building.max' => '建物名は255文字以内で入力してください。',
            ]
        );

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