<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '管理画面') - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', 'Yu Gothic', sans-serif; margin: 0; background: #f5f5f5; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
        nav { background: #1e40af; color: #fff; padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; }
        nav a { color: #fff; text-decoration: none; margin-right: 1rem; }
        nav a:hover { text-decoration: underline; }
        .alert { padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 0.5rem 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; font-weight: 600; }
        .btn { display: inline-block; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; border: none; cursor: pointer; font-size: 0.875rem; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #6b7280; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        form.inline { display: inline; }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="number"], select, textarea { padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; }
        label { display: block; margin-bottom: 0.25rem; font-weight: 500; }
        .form-group { margin-bottom: 1rem; }
        .pagination { margin-top: 1rem; }
        .card { background: #fff; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; }
        .badge-draft { background: #e5e7eb; } .badge-review { background: #fef3c7; } .badge-published { background: #d1fae5; } .badge-archived { background: #f3f4f6; }
    </style>
    @stack('styles')
</head>
<body>
    <nav>
        <div>
            <a href="{{ route('admin.dashboard') }}">管理画面</a>
            <a href="{{ route('admin.knowledge.index') }}">ナレッジ</a>
            @if(auth()->user()->canEdit())
                <a href="{{ route('admin.categories.index') }}">カテゴリ</a>
                <a href="{{ route('admin.tags.index') }}">タグ</a>
            @endif
            <a href="{{ route('admin.conversation-logs.index') }}">会話ログ</a>
            <a href="{{ route('admin.operation-logs.index') }}">操作ログ</a>
            @if(auth()->user()->canEdit())
                <a href="{{ route('admin.users.index') }}">ユーザー</a>
            @endif
        </div>
        <div>
            <span>{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">ログアウト</button>
            </form>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
