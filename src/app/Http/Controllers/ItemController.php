<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        return view('items.show', compact('item', 'isLiked'));
    }
}