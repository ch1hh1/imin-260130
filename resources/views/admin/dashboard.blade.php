@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
<h1>利用状況</h1>
<form method="GET" style="margin-bottom: 1rem;">
    <label>期間</label>
    <select name="period">
        <option value="7" {{ $periodDays == 7 ? 'selected' : '' }}>直近7日</option>
        <option value="30" {{ $periodDays == 30 ? 'selected' : '' }}>直近30日</option>
        <option value="90" {{ $periodDays == 90 ? 'selected' : '' }}>直近90日</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">表示</button>
</form>
<div class="card">
    <h2>質問件数</h2>
    <p style="font-size: 1.5rem;">{{ number_format($questionCount) }} 件</p>
</div>
<div class="card">
    <h2>ナレッジ状況</h2>
    <p>公開: {{ $publishedCount }} 件 / 下書き: {{ $draftCount }} 件</p>
</div>
@if($questionCountByCategory->isNotEmpty())
<div class="card">
    <h2>参照されたナレッジ（上位）</h2>
    <table>
        <thead><tr><th>ナレッジ</th><th>参照回数</th></tr></thead>
        <tbody>
            @foreach($questionCountByCategory as $item)
                <tr><td>{{ $item['name'] }}</td><td>{{ $item['count'] }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
