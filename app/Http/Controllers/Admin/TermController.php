<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $term = Term::first();

        return view('admin.terms.index', compact('term'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term)
    {
        $request->validate([
            'content' => 'required',
        ]);
        $term->content = $request->input('content');
        $term->update();

        return redirect()->route('admin.terms.index')->with('flash_message', '利用規約を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
