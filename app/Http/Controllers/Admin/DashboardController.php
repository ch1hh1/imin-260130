<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversationLog;
use App\Models\Knowledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodDays = (int) $request->get('period', 30);
        $from = now()->subDays($periodDays);

        $questionCount = ConversationLog::where('logged_at', '>=', $from)->count();
        $questionCountByCategory = ConversationLog::where('logged_at', '>=', $from)
            ->whereNotNull('ref_knowledge_ids')
            ->where('ref_knowledge_ids', '!=', '')
            ->get()
            ->flatMap(fn ($log) => explode(',', $log->ref_knowledge_ids))
            ->filter()
            ->countBy()
            ->map(function ($count, $knowledgeId) {
                $k = Knowledge::find($knowledgeId);
                return ['name' => $k?->word ?? "ID:{$knowledgeId}", 'count' => $count];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $publishedCount = Knowledge::where('status', 'published')->count();
        $draftCount = Knowledge::where('status', 'draft')->count();

        return view('admin.dashboard', compact(
            'questionCount',
            'questionCountByCategory',
            'publishedCount',
            'draftCount',
            'periodDays'
        ));
    }
}
