<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use function Laravel\Prompts\text;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Auth::User()->reservations()->orderBy('created_at', 'desc')->paginate(20);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Restaurant $restaurant)
    {
        return view("reservations.create", compact("restaurant"));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'reservation_date' => 'required|date_format:Y-m-d',
            'reservation_time' => 'required|date_format:H:i',
            'number_of_people' => 'required|numeric|between:1,50',
        ]);

        $reservation = new Reservation();

        $reserve_time_str = $request->input('reservation_date') . " " . $request->input('reservation_time');

        $reserve_time = new Datetime($reserve_time_str);

        // 予約できる時間を超えた場合
        if (new DateTime('now') > $reserve_time->modify('-2 hour')) {
            return redirect()->back()->with('error_message', "予約可能な時間を過ぎています。現在より2時間後以降での予約をお願いします。");
        }

        $reservation->reserved_datetime = $reserve_time_str;

        $reservation->number_of_people = $request->input('number_of_people');
        $reservation->restaurant_id = $restaurant->id;
        $reservation->user_id = Auth::user()->id;

        $reservation->save();

        return redirect()->route('reservations.index')->with('flash_message', '予約が完了しました。');
    }

    public function show(Reservation $reservation)
    {
        //
    }

    public function destroy(Restaurant $restaurant, Reservation $reservation)
    {
        // なりすましチェック
        if (!Auth::user()->id == $reservation->user_id) {
            return redirect()->route('reservations.index')->with('error_message', '不正なアクセスです。');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')->with('flash_message', '予約を削除しました。');
    }
}
