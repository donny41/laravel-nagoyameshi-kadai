<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorite_restaurants = Auth::user()->favorite_restaurants()->orderBy('created_at','desc')->paginate(15);
        return view("favorites.index", compact("favorite_restaurants"));
    }

    public function store($restaurant_id)
    {
        Log::debug($restaurant_id);
        Auth::user()->favorite_restaurants()->attach($restaurant_id);
        
        return back()->with('flash_message','お気に入りに追加しました。');
    }

    public function destroy(User $user, Restaurant $restaurant)
    {
        Auth::user()->favorite_restaurants()->detach($user->id);
        
        return back()->with('flash_message','お気に入りを解除しました。');
    }

}
