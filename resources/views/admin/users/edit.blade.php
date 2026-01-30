@extends('layouts.admin')

@section('title', 'ユーザー編集')

@section('content')
<h1>ユーザー編集</h1>
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">名前 *</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
    </div>
    <div class="form-group">
        <label>メール</label>
        <p>{{ e($user->email) }}</p>
    </div>
    <div class="form-group">
        <label for="role_id">ロール *</label>
        <select id="role_id" name="role_id" required>
            @foreach($roles as $r)
                <option value="{{ $r->id }}" {{ old('role_id', $user->role_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">一覧へ</a>
</form>
@endsection
