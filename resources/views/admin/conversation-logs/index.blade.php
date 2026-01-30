@extends('layouts.admin')

@section('title', '会話ログ')

@section('content')
<h1>会話ログ</h1>
<form method="GET" style="margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: flex-end;">
    <div class="form-group" style="margin: 0;">
        <label>日付（から）</label>
        <input type="date" name="from" value="{{ request('from') }}">
    </div>
    <div class="form-group" style="margin: 0;">
        <label>日付（まで）</label>
        <input type="date" name="to" value="{{ request('to') }}">
    </div>
    <button type="submit" class="btn btn-primary">検索</button>
</form>
<p><a href="{{ route('admin.conversation-logs.export', request()->query()) }}" class="btn btn-secondary">CSVエクスポート</a></p>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>日時</th>
            <th>ユーザー</th>
            <th>質問</th>
            <th>回答</th>
            <th>参照ナレッジID</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->logged_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $log->user_id ? $log->user?->name : ('匿名:' . \Illuminate\Support\Str::limit($log->anonymous_id ?? '', 8)) }}</td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ \Illuminate\Support\Str::limit($log->question, 50) }}</td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ \Illuminate\Support\Str::limit($log->answer_text, 50) }}</td>
                <td>{{ $log->ref_knowledge_ids }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $logs->withQueryString()->links() }}
@endsection
