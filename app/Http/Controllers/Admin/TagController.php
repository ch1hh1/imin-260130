<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
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
        $tags = Tag::orderBy('name')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);
        Tag::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'タグを追加しました。');
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);
        $tag->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'タグを更新しました。');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('success', 'タグを削除しました。');
    }