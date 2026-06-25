<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('mypage.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png'],
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required'],
            'address' => ['required', 'string'],
            'building' => ['nullable', 'string'],
        ]);

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

        return redirect('/')->with('message', 'プロフィールを更新しました。');
    }
}