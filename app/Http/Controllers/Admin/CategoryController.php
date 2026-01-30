<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->canEdit()) {
                abort(403, '編集する権限がありません。');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'カテゴリを追加しました。');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);
        $category->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'カテゴリを更新しました。');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'カテゴリを削除しました。');
    }
}
