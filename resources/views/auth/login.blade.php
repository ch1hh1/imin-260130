@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 3rem;">
    <h1>ログイン</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"> ログイン状態を保持</label>
        </div>
        <button type="submit" class="btn btn-primary">ログイン</button>
    </form>
    <p style="margin-top: 1rem;"><a href="{{ url('/chat') }}">一般利用者向け画面へ</a></p>
</div>
@endsection
