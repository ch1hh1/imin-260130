<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversationLog;
use Illuminate\Http\Request;

class ConversationLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ConversationLog::with('user')->orderByDesc('logged_at');
        if ($request->filled('from')) {
            $query->where('logged_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('logged_at', '<=', $request->to . ' 23:59:59');
        }
        $logs = $query->paginate(20);
        return view('admin.conversation-logs.index', compact('logs'));
    }
}
