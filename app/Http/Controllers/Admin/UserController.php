<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->canEdit()) {
                abort(403, '編集する権限がありません。');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::with('role')->orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('id')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);
        $user->update($validated);
        \App\Models\OperationLog::create([
            'user_id' => auth()->id(),
            'action' => 'role_changed',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => "ロール変更: {$user->name}",
        ]);
        return redirect()->route('admin.users.index')->with('success', 'ユーザーを更新しました。');
    }
}
