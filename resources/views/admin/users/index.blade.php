@extends('layouts.admin')

@section('title', 'ユーザー')

@section('content')
<h1>ユーザー一覧</h1>
<table>
    <thead>
        <tr><th>ID</th><th>名前</th><th>メール</th><th>ロール</th><th>操作</th></tr>
    </thead>
    <tbody>
        @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ e($u->name) }}</td>
                <td>{{ e($u->email) }}</td>
                <td>{{ $u->role?->name }}</td>
                <td><a href="{{ route('admin.users.edit', $u) }}" class="btn btn-secondary btn-sm">編集</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $users->links() }}
@endsection
