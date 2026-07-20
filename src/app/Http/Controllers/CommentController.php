<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ((int) $item->user_id === (int) Auth::id()) {
            return redirect()->route('items.show', ['item_id' => $item_id]);
        }

        $request->validate(
            [
                'comment' => ['required', 'string', 'max:255'],
            ],
            [
                'comment.required' => 'コメントを入力してください。',
                'comment.string' => 'コメントは文字列で入力してください。',
                'comment.max' => 'コメントは255文字以内で入力してください。',
            ]
);
        DB::table('comments')->insert([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back();
    }
}