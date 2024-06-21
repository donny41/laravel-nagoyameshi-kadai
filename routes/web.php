<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController as UR;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\Subscribed;
use App\Http\Middleware\NotSubscribed;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

require __DIR__ . '/auth.php';

/*
このようにRouteファサードのgroup()メソッドを使い、配列で'prefix'や'as'を指定することで、'prefix'の場合はURLの先頭、'as'の場合は名前付きルートの先頭を設定できます。

つまり以下の例であれば、グループ内で設定しているルートのURLが'admin/home'、名前付きルートが'admin.home'となります。
*/
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'auth:admin'
], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    // {user}の名称は show.blade.php 内で指定している $user を入れる
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');

    Route::resource('restaurants', RestaurantController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('terms', TermController::class);
});

// guest:adminの挙動: adminはadmin.homeへリダイレクト、それ以外はOK。
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/restaurants', [UR::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{restaurant}', [UR::class, 'show'])->name('restaurants.show');
});


// auth:web = WEB（ユーザー）として認証のみ
Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update');

    // review 一括のコード例（使わない）
    // Route::resource('restaurants.reviews', ReviewController::class);

    // レビュー機能のうち一般ユーザー向け
    Route::get('/restaurants/{restaurant}/reviews', [ReviewController::class, 'index'])->name('restaurants.reviews.index');

    // サブスクなし
    Route::group(['middleware' => 'not_subscribed'], function () {
        Route::get('/subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
        Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
    });

    // サブスクあり
    Route::group(['middleware' => 'subscribed'], function () {
        Route::get('/subscription/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
        Route::patch('/subscription/update', [SubscriptionController::class, 'update'])->name('subscription.update');
        Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::delete('/subscription/destroy', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');

        // レビュー機能
        Route::get('/restaurants/{restaurant}/reviews/create', [ReviewController::class, 'create'])->name('restaurants.reviews.create');
        Route::post('/restaurants/{restaurant}/reviews', [ReviewController::class, 'store'])->name('restaurants.reviews.store');
        Route::get('/restaurants/{restaurant}/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('restaurants.reviews.edit');
        Route::patch('/restaurants/{restaurant}/reviews/{review}', [ReviewController::class, 'update'])->name('restaurants.reviews.update');
        Route::delete('/restaurants/{restaurant}/reviews/{review}', [ReviewController::class, 'destroy'])->name('restaurants.reviews.destroy');
    
        // 予約機能
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/restaurants/{restaurant}/reservations/create', [ReservationController::class, 'create'])->name('restaurants.reservations.create');
        Route::post('/restaurants/{restaurant}/reservations', [ReservationController::class, 'store'])->name('restaurants.reservations.store');
        Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    });
});