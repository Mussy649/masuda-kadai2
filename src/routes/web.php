<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;

Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return redirect()->route('profile.edit');
    });

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});
