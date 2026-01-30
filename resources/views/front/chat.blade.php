@extends('layouts.app')

@section('title', 'AIご案内係')

@section('content')
<nav style="background: #2563eb; color: #fff; padding: 0.75rem 1rem;">
    <a href="{{ url('/chat') }}" style="color: #fff; text-decoration: none;">{{ config('app.name') }}</a>
    @auth
        <a href="{{ route('admin.dashboard') }}" style="color: #fff; margin-left: 1rem;">管理画面</a>
    @endauth
    @guest
        <a href="{{ route('login') }}" style="color: #fff; margin-left: 1rem;">管理画面ログイン</a>
    @endguest
</nav>
<div class="container" style="max-width: 800px; margin-top: 1rem;">
    @if($disclaimer)
        <p class="alert" style="background: #fef3c7; color: #92400e;">{{ $disclaimer }}</p>
    @endif
    <div id="chat-messages">
        @foreach($messages as $msg)
            <div class="card" style="margin-bottom: 1rem;">
                <div><strong>質問:</strong> {{ e($msg->question) }}</div>
                <div style="margin-top: 0.5rem;"><strong>回答:</strong><br>{!! nl2br(e($msg->answer_text)) !!}</div>
                @if($showSources && $msg->ref_knowledge_ids)
                    @php $ids = explode(',', $msg->ref_knowledge_ids); @endphp
                    <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">参照元: （ナレッジID: {{ implode(', ', $ids) }}）</div>
                @endif
            </div>
        @endforeach
    </div>
    <form id="ask-form" style="margin-top: 1rem;">
        @csrf
        <input type="hidden" name="session_id" id="session_id" value="{{ $session->id }}">
        <div class="form-group">
            <label for="question">質問を入力</label>
            <textarea id="question" name="question" rows="3" maxlength="{{ $maxLength }}" placeholder="質問を入力してください" required style="width: 100%; max-width: none;"></textarea>
            <span id="char-count">0</span> / {{ $maxLength }} 文字
        </div>
        <button type="submit" class="btn btn-primary">送信</button>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ask-form');
    const question = document.getElementById('question');
    const messagesDiv = document.getElementById('chat-messages');
    const charCount = document.getElementById('char-count');
    const maxLen = {{ $maxLength }};
    const showSources = @json($showSources);

    question.addEventListener('input', function() { charCount.textContent = this.value.length; });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        const fd = new FormData(form);
        fd.set('question', question.value.trim());
        fetch('{{ route("front.chat.ask") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            const card = document.createElement('div');
            card.className = 'card';
            card.style.marginBottom = '1rem';
            let refHtml = '';
            if (showSources && data.sources && data.sources.length) {
                refHtml = '<div style="margin-top:0.5rem;font-size:0.875rem;color:#6b7280;">参照元: ' +
                    data.sources.map(s => s.word + (s.category ? ' (' + s.category + ')' : '')).join(', ') + '</div>';
            }
            card.innerHTML = '<div><strong>質問:</strong> ' + escapeHtml(question.value) + '</div>' +
                '<div style="margin-top:0.5rem;"><strong>回答:</strong><br>' + escapeHtml(data.answer_text).replace(/\n/g, '<br>') + '</div>' + refHtml;
            messagesDiv.appendChild(card);
            question.value = '';
            charCount.textContent = '0';
        })
        .catch(err => alert('送信に失敗しました。'))
        .finally(() => { btn.disabled = false; });
    });

    function escapeHtml(s) {
        const div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }
});
</script>
@endsection
