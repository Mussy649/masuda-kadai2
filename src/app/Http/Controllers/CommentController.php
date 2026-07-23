<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ((int) $item->user_id === (int) Auth::id()) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $validated = $request->validated();

        DB::table('comments')->insert([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $validated['comment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back();
    }
}