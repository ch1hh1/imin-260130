<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEditorRole
{
    /** 編集・承認可能: 管理者・編集者 */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (! in_array($user->role->name ?? '', ['管理者', '編集者'], true)) {
            abort(403, '編集・承認する権限がありません。');
        }

        return $next($request);
    }
}
