<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RegularHoliday;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $restaurants = Restaurant::where("name", "like", "%{$keyword}%")->orderBy('id', 'asc')->paginate(20);
            $total = $restaurants->total();
        } else {
            $restaurants = Restaurant::orderBy('id', 'asc')->paginate(20);
            $total = $restaurants->total();
        }

        return view('admin.restaurants.index', compact('restaurants', 'total', 'keyword'));

    }

    public function create()
    {
        $categories = Category::all();
        $regular_holidays = RegularHoliday::all();
        return view('admin.restaurants.create', compact('categories','regular_holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0|lt:highest_price',
            'highest_price' => 'required|numeric|min:0|gt:lowest_price',
            'postal_code' => 'required|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
        ]);

        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        }
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        $restaurant->save();

        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        $regular_holiday_ids = array_filter($request->input('regular_holiday_ids'));
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
    }

    public function show(Restaurant $restaurant)
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        $categories = Category::all();
        $category_ids = $restaurant->categories->pluck('id')->toArray();
        $regular_holidays = RegularHoliday::all();

        return view('admin.restaurants.edit', compact('restaurant', 'categories', 'category_ids','regular_holidays'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0|lt:highest_price',
            'highest_price' => 'required|numeric|min:0|gt:lowest_price',
            'postal_code' => 'required|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
        ]);

        // $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        }
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        // $restaurant->save();
        $restaurant->update();

        $category_ids = array_filter(($request->input('category_ids')));
        $restaurant->categories()->sync($category_ids);

        $regular_holiday_ids = array_filter($request->input('regular_holiday_ids'));
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗情報を更新しました。');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
    }
}
