@extends('layouts.admin')

@section('title', '操作ログ')

@section('content')
<h1>操作ログ</h1>
<form method="GET" style="margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: flex-end;">
    <div class="form-group" style="margin: 0;">
        <label>操作</label>
        <select name="action">
            <option value="">すべて</option>
            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>登録</option>
            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>更新</option>
            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>削除</option>
            <option value="status_changed" {{ request('action') == 'status_changed' ? 'selected' : '' }}>状態変更</option>
            <option value="role_changed" {{ request('action') == 'role_changed' ? 'selected' : '' }}>権限変更</option>
        </select>
    </div>
    <div class="form-group" style="margin: 0;">
        <label>対象タイプ</label>
        <select name="target_type">
            <option value="">すべて</option>
            <option value="knowledge" {{ request('target_type') == 'knowledge' ? 'selected' : '' }}>ナレッジ</option>
            <option value="user" {{ request('target_type') == 'user' ? 'selected' : '' }}>ユーザー</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">検索</button>
</form>
<p><a href="{{ route('admin.operation-logs.export', request()->query()) }}" class="btn btn-secondary">CSVエクスポート</a></p>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>日時</th>
            <th>ユーザー</th>
            <th>操作</th>
            <th>対象タイプ</th>
            <th>対象ID</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $log->user?->name }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->target_type }}</td>
                <td>{{ $log->target_id }}</td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ \Illuminate\Support\Str::limit($log->details, 50) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $logs->withQueryString()->links() }}
@endsection
