<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.restaurants.create');

    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
    }
}
