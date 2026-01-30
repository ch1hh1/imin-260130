<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /** 管理画面（閲覧以上）: 管理者・編集者・閲覧者 */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (! in_array($user->role->name ?? '', ['管理者', '編集者', '閲覧者'], true)) {
            abort(403, 'この画面にアクセスする権限がありません。');
        }

        return $next($request);
    }
}
