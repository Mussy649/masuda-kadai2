<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| 公開ルート
|--------------------------------------------------------------------------
| 未ログインでも閲覧できる画面
*/

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

/*
|--------------------------------------------------------------------------
| ログイン必須ルート
|--------------------------------------------------------------------------
| ここから下は auth ミドルウェア内
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // /home
    // マイページ
    // 出品
    // いいね
    // コメント
    // 購入


    /*
    |--------------------------------------------------------------------------
    | ログイン後リダイレクト
    |--------------------------------------------------------------------------
    */

    Route::get('/home', function () {
        $user = Auth::user();

        if ($user->hasCompletedProfile()) {
            return redirect()->route('items.index');
        }

        return redirect()->route('mypage.profile.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | マイページ
    |--------------------------------------------------------------------------
    */

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit');
    Route::patch('/mypage/profile', [MypageController::class, 'update'])->name('mypage.profile.update');

    /*
    |--------------------------------------------------------------------------
    | 商品出品
    |--------------------------------------------------------------------------
    */

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    /*
    |--------------------------------------------------------------------------
    | いいね
    |--------------------------------------------------------------------------
    */

    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    /*
    |--------------------------------------------------------------------------
    | コメント
    |--------------------------------------------------------------------------
    */

    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    /*
    |--------------------------------------------------------------------------
    | 商品購入
    |--------------------------------------------------------------------------
    */

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::put('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
    Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel/{item_id}', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

});