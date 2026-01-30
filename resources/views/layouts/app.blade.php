<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', 'Yu Gothic', sans-serif; margin: 0; background: #f5f5f5; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
        nav { background: #2563eb; color: #fff; padding: 0.75rem 1rem; }
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
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="number"], select, textarea { padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; width: 100%; max-width: 400px; }
        label { display: block; margin-bottom: 0.25rem; font-weight: 500; }
        .form-group { margin-bottom: 1rem; }
        .pagination { margin-top: 1rem; }
        .pagination a, .pagination span { padding: 0.25rem 0.5rem; margin-right: 0.25rem; }
    </style>
    @stack('styles')
</head>
<body>
    @if(session('success'))
        <div class="container"><div class="alert alert-success">{{ session('success') }}</div></div>
    @endif
    @if($errors->any())
        <div class="container">
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif
    @yield('content')
    @stack('scripts')
</body>
</html>
