@extends('layouts.admin')

@section('title', 'ナレッジ一覧')

@section('content')
<h1>ナレッジ一覧</h1>
@if(auth()->user()->canEdit())
<p><a href="{{ route('admin.knowledge.create') }}" class="btn btn-primary">新規登録</a></p>
@endif
<form method="GET" style="margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: flex-end;">
    <div class="form-group" style="margin: 0;">
        <label>キーワード</label>
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="検索">
    </div>
    <div class="form-group" style="margin: 0;">
        <label>カテゴリ</label>
        <select name="category_id">
            <option value="">すべて</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group" style="margin: 0;">
        <label>タグ</label>
        <select name="tag_id">
            <option value="">すべて</option>
            @foreach($tags as $t)
                <option value="{{ $t->id }}" {{ request('tag_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group" style="margin: 0;">
        <label>状態</label>
        <select name="status">
            <option value="">すべて</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>下書き</option>
            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>レビュー中</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>公開</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>アーカイブ</option>
        </select>
    </div>
    <div class="form-group" style="margin: 0;">
        <label>改定日（から）</label>
        <input type="date" name="revised_from" value="{{ request('revised_from') }}">
    </div>
    <div class="form-group" style="margin: 0;">
        <label>改定日（まで）</label>
        <input type="date" name="revised_to" value="{{ request('revised_to') }}">
    </div>
    <button type="submit" class="btn btn-primary">検索</button>
</form>
<table>
    <thead>
        <tr>
            <th>タイトル</th>
            <th>カテゴリ</th>
            <th>状態</th>
            <th>改定日</th>
            <th>更新日</th>
            @if(auth()->user()->canEdit())<th>操作</th>@endif
        </tr>
    </thead>
    <tbody>
        @foreach($knowledgeList as $k)
            <tr>
                <td><a href="{{ route('admin.knowledge.show', $k) }}">{{ e($k->word) }}</a></td>
                <td>{{ $k->category?->name }}</td>
                <td><span class="badge badge-{{ $k->status }}">{{ $k->status }}</span></td>
                <td>{{ $k->revised_at?->format('Y-m-d') }}</td>
                <td>{{ $k->updated_at->format('Y-m-d H:i') }}</td>
                @if(auth()->user()->canEdit())
                <td>
                    <a href="{{ route('admin.knowledge.edit', $k) }}" class="btn btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.knowledge.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
{{ $knowledgeList->withQueryString()->links() }}
@endsection
