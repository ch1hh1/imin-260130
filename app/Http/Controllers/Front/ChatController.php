<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ConversationLog;
use App\Models\ConversationMessage;
use App\Services\KnowledgeSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct(
        private KnowledgeSearchService $searchService
    ) {}

    public function index(Request $request)
    {
        $sessionId = $request->input('session_id') ?: $request->session()->get('chat_session_id');
        $session = $this->getOrCreateSession($request, $sessionId);
        $request->session()->put('chat_session_id', $session->id);
        $messages = $session->messages()->orderBy('created_at')->get();
        $disclaimer = config('ai_annai.disclaimer');
        $showSources = config('ai_annai.show_sources');
        $maxLength = config('ai_annai.question_max_length');

        return view('front.chat', compact('session', 'messages', 'disclaimer', 'showSources', 'maxLength'));
    }

    public function ask(Request $request)
    {
        $maxLength = config('ai_annai.question_max_length');
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:' . $maxLength],
            'session_id' => ['nullable', 'exists:chat_sessions,id'],
        ]);

        $sessionId = $validated['session_id'] ?? $request->session()->get('chat_session_id');
        $session = $this->getOrCreateSession($request, $sessionId);
        $request->session()->put('chat_session_id', $session->id);
        $this->touchSession($session);

        $question = $validated['question'];
        $context = $session->messages()->orderByDesc('created_at')->first()?->question;
        $results = $this->searchService->search($question, $context);

        $answerText = '';
        $refIds = [];
        $sources = [];
        if ($results->isNotEmpty()) {
            $first = $results->first();
            $answerText = $first->detail;
            $refIds = $results->take(5)->pluck('id')->map(fn ($id) => (string) $id)->toArray();
            $sources = $results->take(5)->map(fn ($k) => [
                'id' => $k->id,
                'word' => $k->word,
                'category' => $k->category?->name,
                'version' => $k->version,
                'revised_at' => $k->revised_at?->format('Y-m-d'),
            ])->toArray();
        } else {
            $answerText = '申し訳ございません。該当する情報が見つかりませんでした。別のキーワードでお試しください。';
        }

        $message = ConversationMessage::create([
            'chat_session_id' => $session->id,
            'question' => $question,
            'answer_text' => $answerText,
            'ref_knowledge_ids' => implode(',', $refIds),
        ]);

        ConversationLog::create([
            'user_id' => Auth::id(),
            'anonymous_id' => $session->anonymous_id,
            'chat_session_id' => $session->id,
            'question' => $question,
            'answer_text' => $answerText,
            'ref_knowledge_ids' => implode(',', $refIds),
            'logged_at' => now(),
        ]);

        $showSources = config('ai_annai.show_sources');

        return response()->json([
            'message_id' => $message->id,
            'answer_text' => $answerText,
            'sources' => $showSources ? $sources : [],
        ]);
    }

    public function session(Request $request, ChatSession $session)
    {
        $this->touchSession($session);
        $messages = $session->messages()->orderBy('created_at')->get();
        $disclaimer = config('ai_annai.disclaimer');
        $showSources = config('ai_annai.show_sources');
        $maxLength = config('ai_annai.question_max_length');

        return view('front.chat', compact('session', 'messages', 'disclaimer', 'showSources', 'maxLength'));
    }

    private function getOrCreateSession(Request $request, ?int $sessionId = null): ChatSession
    {
        $timeoutMinutes = config('ai_annai.session_timeout_minutes', 30);

        if ($sessionId) {
            $session = ChatSession::find($sessionId);
            if ($session && $session->last_activity_at && $session->last_activity_at->diffInMinutes(now()) < $timeoutMinutes) {
                return $session;
            }
        }

        $session = new ChatSession;
        $session->user_id = Auth::id();
        if (! Auth::check()) {
            $session->anonymous_id = $request->session()->get('chat_anonymous_id') ?: Str::random(40);
            $request->session()->put('chat_anonymous_id', $session->anonymous_id);
        }
        $session->last_activity_at = now();
        $session->save();

        return $session;
    }

    private function touchSession(ChatSession $session): void
    {
        $session->update(['last_activity_at' => now()]);
    }
}
