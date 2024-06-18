<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Barryvdh\Debugbar\Facade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sorts = [
            '新着順' => 'created_at desc',
        ];

        $sort_query = [];
        $sorted = "created_at desc";

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }


        $total = 1;
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $users = User::where("name", "like", "%{$keyword}%")->sortable($sort_query)->orderBy('id', 'asc')->paginate(20);
            $total = $users->total();
        } else {
            $users = User::sortable($sort_query)->orderBy('id', 'asc')->paginate(20);
            $total = $users->total();
        }

        return view('admin.users.index', compact('users', 'total', 'keyword'));
    }

    public function show(User $user)
    {
        Log::debug(gettype($user->id) . ":" . ($user->id));

        return view('admin.users.show', compact('user'));
    }

}
