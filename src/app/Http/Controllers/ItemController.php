<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $items = collect();

                return view('items.index', compact('items'));
            }

            $likedItemIds = DB::table('likes')
                ->where('user_id', Auth::id())
                ->pluck('item_id');

            $query = Item::whereIn('id', $likedItemIds);
        } else {
            $query = Item::query();
        }

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        $query->with('purchase');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $items = $query->latest()->get();

        return view('items.index', compact('items'));
    }

    public function show($item_id)
    {
        $item = Item::with(['condition', 'categories', 'user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($item_id);

        $isLiked = false;

        if (Auth::check()) {
            $isLiked = DB::table('likes')
                ->where('user_id', Auth::id())
                ->where('item_id', $item_id)
                ->exists();
        }

        $isPurchased = DB::table('purchases')
            ->where('item_id', $item_id)
            ->exists();

        $isOwnItem = Auth::check() && (int) $item->user_id === (int) Auth::id();

        return view('items.show', compact('item', 'isLiked', 'isPurchased', 'isOwnItem'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'category_ids' => ['required', 'array'],
                'category_ids.*' => ['exists:categories,id'],
                'condition_id' => ['required', 'exists:conditions,id'],
                'name' => ['required', 'string', 'max:255'],
                'brand_name' => ['nullable', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'price' => ['required', 'integer', 'min:1'],
            ],
            [
                'image.required' => '商品画像を選択してください。',
                'image.image' => '商品画像は画像ファイルを選択してください。',
                'image.mimes' => '商品画像はjpeg、png、jpg形式で選択してください。',
                'image.max' => '商品画像は2MB以内で選択してください。',

                'category_ids.required' => 'カテゴリーを選択してください。',
                'category_ids.array' => 'カテゴリーの選択形式が正しくありません。',
                'category_ids.*.exists' => '選択されたカテゴリーが正しくありません。',

                'condition_id.required' => '商品の状態を選択してください。',
                'condition_id.exists' => '選択された商品の状態が正しくありません。',

                'name.required' => '商品名を入力してください。',
                'name.string' => '商品名は文字列で入力してください。',
                'name.max' => '商品名は255文字以内で入力してください。',

                'brand_name.string' => 'ブランド名は文字列で入力してください。',
                'brand_name.max' => 'ブランド名は255文字以内で入力してください。',

                'description.required' => '商品の説明を入力してください。',
                'description.string' => '商品の説明は文字列で入力してください。',

                'price.required' => '販売価格を入力してください。',
                'price.integer' => '販売価格は整数で入力してください。',
                'price.min' => '販売価格は1円以上で入力してください。',
            ]
        );

        $imagePath = null;

        try {
            $imagePath = $request->file('image')->store('items', 'public');

            DB::transaction(function () use ($request, $imagePath) {
                $item = new Item();
                $item->user_id = Auth::id();
                $item->condition_id = $request->condition_id;
                $item->name = $request->name;
                $item->brand_name = $request->brand_name;
                $item->description = $request->description;
                $item->price = $request->price;
                $item->image_path = $imagePath;
                $item->save();

                foreach ($request->category_ids as $categoryId) {
                    DB::table('category_item')->insert([
                        'item_id' => $item->id,
                        'category_id' => $categoryId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            return redirect()->route('mypage.index', ['page' => 'sell']);
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()
                ->withInput()
                ->withErrors([
                    'item' => '商品登録中にエラーが発生しました。もう一度お試しください。',
                ]);
        }
    }
}