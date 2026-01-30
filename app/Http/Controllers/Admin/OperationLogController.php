<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationLog;
use Illuminate\Http\Request;

class OperationLogController extends Controller
{
    public function index(Request $request)
    {
        $query = OperationLog::with('user')->orderByDesc('created_at');
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }
        $logs = $query->paginate(20);
        return view('admin.operation-logs.index', compact('logs'));
    }
}
