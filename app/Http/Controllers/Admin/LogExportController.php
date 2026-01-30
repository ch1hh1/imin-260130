<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversationLog;
use App\Models\OperationLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogExportController extends Controller
{
    public function conversationLogs(Request $request): StreamedResponse
    {
        $query = ConversationLog::with('user')->orderBy('logged_at');
        if ($request->filled('from')) {
            $query->where('logged_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('logged_at', '<=', $request->to . ' 23:59:59');
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="conversation_logs_' . date('Y-m-d_His') . '.csv"',
        ];

        return response()->stream(function () use ($query) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['ID', 'ユーザーID', '匿名ID', '質問', '回答', '参照ナレッジID', '記録日時']);
            $query->chunk(100, function ($logs) use ($stream) {
                foreach ($logs as $log) {
                    fputcsv($stream, [
                        $log->id,
                        $log->user_id,
                        $log->anonymous_id,
                        $log->question,
                        $log->answer_text,
                        $log->ref_knowledge_ids,
                        $log->logged_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });
            fclose($stream);
        }, 200, $headers);
    }

    public function operationLogs(Request $request): StreamedResponse
    {
        $query = OperationLog::with('user')->orderBy('created_at');
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->to . ' 23:59:59');
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="operation_logs_' . date('Y-m-d_His') . '.csv"',
        ];

        return response()->stream(function () use ($query) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['ID', 'ユーザーID', '操作', '対象タイプ', '対象ID', '詳細', '日時']);
            $query->chunk(100, function ($logs) use ($stream) {
                foreach ($logs as $log) {
                    fputcsv($stream, [
                        $log->id,
                        $log->user_id,
                        $log->action,
                        $log->target_type,
                        $log->target_id,
                        $log->details,
                        $log->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });
            fclose($stream);
        }, 200, $headers);
    }
}
