<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Knowledge;
use App\Models\Category;
use App\Models\Tag;
use App\Models\OperationLog;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->canEdit()) {
                abort(403, '編集する権限がありません。');
            }
            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy', 'updateStatus']);
    }

    public function index(Request $request)
    {
        $query = Knowledge::with(['category', 'tags', 'creator']);

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('word', 'like', "%{$kw}%")->orWhere('detail', 'like', "%{$kw}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $request->tag_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('revised_from')) {
            $query->where('revised_at', '>=', $request->revised_from);
        }
        if ($request->filled('revised_to')) {
            $query->where('revised_at', '<=', $request->revised_to);
        }

        $knowledgeList = $query->orderByDesc('updated_at')->paginate(15);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.knowledge.index', compact('knowledgeList', 'categories', 'tags'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.knowledge.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'revised_at' => ['nullable', 'date'],
            'version' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:draft,review,published,archived'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $status = $validated['status'];
        if (! auth()->user()->canEdit()) {
            $status = 'draft';
        }

        $knowledge = Knowledge::create([
            'word' => $validated['word'],
            'detail' => $validated['detail'],
            'category_id' => $validated['category_id'],
            'revised_at' => $validated['revised_at'],
            'version' => $validated['version'],
            'status' => $status,
            'created_by' => auth()->id(),
        ]);
        if (! empty($validated['tag_ids'])) {
            $knowledge->tags()->sync($validated['tag_ids']);
        }

        OperationLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'knowledge',
            'target_id' => $knowledge->id,
            'details' => $knowledge->word,
        ]);

        return redirect()->route('admin.knowledge.index')->with('success', 'ナレッジを登録しました。');
    }

    public function show(Knowledge $knowledge)
    {
        $knowledge->load(['category', 'tags', 'creator']);
        return view('admin.knowledge.show', compact('knowledge'));
    }

    public function edit(Knowledge $knowledge)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $knowledge->load('tags');
        return view('admin.knowledge.edit', compact('knowledge', 'categories', 'tags'));
    }

    public function update(Request $request, Knowledge $knowledge)
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'revised_at' => ['nullable', 'date'],
            'version' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:draft,review,published,archived'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $knowledge->update([
            'word' => $validated['word'],
            'detail' => $validated['detail'],
            'category_id' => $validated['category_id'],
            'revised_at' => $validated['revised_at'],
            'version' => $validated['version'],
            'status' => $validated['status'],
        ]);
        $knowledge->tags()->sync($validated['tag_ids'] ?? []);

        OperationLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'knowledge',
            'target_id' => $knowledge->id,
            'details' => $knowledge->word,
        ]);

        return redirect()->route('admin.knowledge.index')->with('success', 'ナレッジを更新しました。');
    }

    public function destroy(Knowledge $knowledge)
    {
        $word = $knowledge->word;
        $id = $knowledge->id;
        $knowledge->delete();
        OperationLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'knowledge',
            'target_id' => $id,
            'details' => $word,
        ]);
        return redirect()->route('admin.knowledge.index')->with('success', 'ナレッジを削除しました。');
    }

    public function updateStatus(Request $request, Knowledge $knowledge)
    {
        $validated = $request->validate(['status' => ['required', 'in:draft,review,published,archived']]);
        $oldStatus = $knowledge->status;
        $knowledge->update(['status' => $validated['status']]);
        OperationLog::create([
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'target_type' => 'knowledge',
            'target_id' => $knowledge->id,
            'details' => "{$oldStatus} → {$validated['status']}",
        ]);
        return redirect()->back()->with('success', '状態を更新しました。');
    }
}
