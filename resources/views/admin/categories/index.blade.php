@extends('layouts.admin')

@section('title', 'カテゴリ')

@section('content')
<h1>カテゴリ一覧</h1>
@if(auth()->user()->canEdit())
<form method="POST" action="{{ route('admin.categories.store') }}" style="margin-bottom: 1rem;">
    @csrf
    <div class="form-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
        <div style="margin: 0;">
            <label for="name">新規カテゴリ名</label>
            <input id="name" type="text" name="name" required maxlength="255">
        </div>
        <button type="submit" class="btn btn-primary">追加</button>
    </div>
</form>
@endif
<table>
    <thead><tr><th>ID</th><th>名前</th>@if(auth()->user()->canEdit())<th>操作</th>@endif</tr></thead>
    <tbody>
        @foreach($categories as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>
                    @if(auth()->user()->canEdit())
                    <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="inline" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ e($c->name) }}" required maxlength="255" style="width: 200px;">
                        <button type="submit" class="btn btn-secondary btn-sm">更新</button>
                    </form>
                    @else
                        {{ $c->name }}
                    @endif
                </td>
                @if(auth()->user()->canEdit())
                <td>
                    <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('削除しますか？');">
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
@endsection
