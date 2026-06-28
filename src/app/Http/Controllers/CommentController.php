<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Request $request, $item_id)
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:255'],
        ]);

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