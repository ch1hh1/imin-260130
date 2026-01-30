@extends('layouts.admin')

@section('title', 'ナレッジ登録')

@section('content')
<h1>ナレッジ登録</h1>
<form method="POST" action="{{ route('admin.knowledge.store') }}">
    @csrf
    <div class="form-group">
        <label for="word">タイトル（ワード） *</label>
        <input id="word" type="text" name="word" value="{{ old('word') }}" required maxlength="255">
    </div>
    <div class="form-group">
        <label for="detail">詳細（本文） *</label>
        <textarea id="detail" name="detail" rows="10" required>{{ old('detail') }}</textarea>
    </div>
    <div class="form-group">
        <label for="category_id">カテゴリ</label>
        <select id="category_id" name="category_id">
            <option value="">選択なし</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="revised_at">改定日</label>
        <input id="revised_at" type="date" name="revised_at" value="{{ old('revised_at') }}">
    </div>
    <div class="form-group">
        <label for="version">版</label>
        <input id="version" type="text" name="version" value="{{ old('version') }}" maxlength="50">
    </div>
    <div class="form-group">
        <label for="status">状態</label>
        <select id="status" name="status">
            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>下書き</option>
            <option value="review">レビュー中</option>
            <option value="published">公開</option>
            <option value="archived">アーカイブ</option>
        </select>
    </div>
    <div class="form-group">
        <label>タグ</label>
        @foreach($tags as $t)
            <label><input type="checkbox" name="tag_ids[]" value="{{ $t->id }}" {{ in_array($t->id, old('tag_ids', [])) ? 'checked' : '' }}> {{ $t->name }}</label>
        @endforeach
    </div>
    <button type="submit" class="btn btn-primary">登録</button>
    <a href="{{ route('admin.knowledge.index') }}" class="btn btn-secondary">一覧へ</a>
</form>
@endsection
