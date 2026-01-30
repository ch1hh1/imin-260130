@extends('layouts.admin')

@section('title', $knowledge->word)

@section('content')
<h1>{{ e($knowledge->word) }}</h1>
<div class="card">
    <p><strong>状態:</strong> <span class="badge badge-{{ $knowledge->status }}">{{ $knowledge->status }}</span></p>
    <p><strong>カテゴリ:</strong> {{ $knowledge->category?->name }}</p>
    <p><strong>改定日:</strong> {{ $knowledge->revised_at?->format('Y-m-d') }}</p>
    <p><strong>版:</strong> {{ $knowledge->version }}</p>
    <p><strong>タグ:</strong> {{ $knowledge->tags->pluck('name')->join(', ') ?: 'なし' }}</p>
    <p><strong>登録者:</strong> {{ $knowledge->creator?->name }}</p>
    <p><strong>更新日:</strong> {{ $knowledge->updated_at->format('Y-m-d H:i') }}</p>
</div>
<div class="card">
    <h3>詳細（本文）</h3>
    <div style="white-space: pre-wrap;">{{ e($knowledge->detail) }}</div>
</div>
@if(auth()->user()->canEdit())
<p>
    <a href="{{ route('admin.knowledge.edit', $knowledge) }}" class="btn btn-primary">編集</a>
    <form action="{{ route('admin.knowledge.destroy', $knowledge) }}" method="POST" class="inline" style="display:inline;" onsubmit="return confirm('削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">削除</button>
    </form>
</p>
@endif
<a href="{{ route('admin.knowledge.index') }}" class="btn btn-secondary">一覧へ</a>
@endsection
