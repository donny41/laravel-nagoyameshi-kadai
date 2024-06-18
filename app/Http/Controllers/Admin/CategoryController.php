<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $categories = Category::where("name", "like", "%{$keyword}%")->orderBy('id', 'asc')->paginate(20);
            $total = $categories->total();
        } else {
            $categories = Category::orderBy('id', 'asc')->paginate(20);
            $total = $categories->total();
        }

        return view('admin.categories.index', compact('categories', 'total', 'keyword'));

    }

    public function create()
    {
        // モーダルの場合は以下のアクションは行わない
        // return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        // return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリーを登録しました。');
        return to_route('admin.categories.index');

    }

    public function show(Category $category)
    {
        // 詳細画面なし
        // return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // モーダルの場合は以下のアクションは行わない
        // return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $category->name = $request->input('name');
        $category->update();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリー情報を更新しました。');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリーを削除しました。');
    }
}
