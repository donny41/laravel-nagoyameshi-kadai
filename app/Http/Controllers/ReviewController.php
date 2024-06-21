<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Restaurant $restaurant)
    {

        // 有料会員かどうかで $restaurant を出し分け
        if (Auth::user()->subscribed('premium_plan')) {
            $reviews = $restaurant->reviews()->orderBy('created_at', 'desc')->paginate(5);
        } else {
            $reviews = $restaurant->reviews()->orderBy('created_at', 'desc')->paginate(5)->take(3);
        }
        
        return view('reviews.index', compact('restaurant', 'reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Restaurant $restaurant)
    {
        return view("reviews.create", compact("restaurant"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'content' => 'required',
            'score' => 'required|between:1,5',
        ]);

        $review = new Review();

        $review->content = $request->input('content');
        $review->score = $request->input('score');
        $review->restaurant_id = $restaurant->id;
        $review->user_id = Auth::user()->id;

        $review->save();

        return redirect()->route('restaurants.reviews.index',$restaurant)->with('flash_message', 'レビューを投稿しました。');
    }

    public function show(Review $review)
    {
        //
    }

    public function edit(Restaurant $restaurant, Review $review)
    {
        // なりすましチェック
        if (!Auth::user()->id == $review->user_id) {
            return redirect()->route('restaurants.reviews.index',$restaurant)->with('error_message','不正なアクセスです。');
        }

        return view('reviews.edit', compact('review', 'restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant, Review $review)
    {
        Log::debug('なりすまし判定用'. '本人は:'. Auth::user()->id .' 更新対象は: '. $review->user_id);

        // なりすましチェック
        if (!Auth::user()->id == $review->user_id) {
            return redirect()->route('restaurants.reviews.index',$restaurant)->with('error_message','不正なアクセスです。');
        }

        $request->validate([
            'content' => 'required',
            'score' => 'required|between:1,5',
        ]);

        $review->content = $request->input('content');
        $review->score = $request->input('score');
        // $review->restaurant_id = $restaurant->id;
        // $review->user_id = Auth::user()->id;

        $review->update();

        return redirect()->route('restaurants.reviews.index',$restaurant)->with('flash_message', 'レビューを編集しました。');
    }

    public function destroy(Restaurant $restaurant, Review $review)
    {
        // なりすましチェック
        if (!Auth::user()->id == $review->user_id) {
            return redirect()->route('restaurants.reviews.index',$restaurant)->with('error_message','不正なアクセスです。');
        }

        $review->delete();

        return redirect()->route('restaurants.reviews.index',$restaurant)->with('flash_message', 'レビューを削除しました。');    }
}
