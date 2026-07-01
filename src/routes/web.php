<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        $user = Auth::user();

        if ($user->hasCompletedProfile()) {
            return redirect()->route('items.index');
        }

        return redirect()->route('mypage.profile.edit');
    });

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit');
    Route::patch('/mypage/profile', [MypageController::class, 'update'])->name('mypage.profile.update');
    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
});