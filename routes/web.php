<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;

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

// 管理者としてログインしていない状態でのみアクセスできる
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});