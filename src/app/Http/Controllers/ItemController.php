<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;

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
        $item = Item::with([
            'condition',
            'categories',
            'user',
            'comments' => function ($query) {
                $query->with('user')
                    ->latest();
            },
        ])
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

        $isOwnItem = Auth::check()
            && (int) $item->user_id === (int) Auth::id();

        return view(
            'items.show',
            compact('item', 'isLiked', 'isPurchased', 'isOwnItem')
        );
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {

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