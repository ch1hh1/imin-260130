<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KnowledgeController as AdminKnowledgeController;
use App\Http\Controllers\Admin\LogExportController;
use App\Http\Controllers\Admin\OperationLogController;
use App\Http\Controllers\Admin\ConversationLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Front\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('front.chat'));
Route::get('/chat', [ChatController::class, 'index'])->name('front.chat');
Route::post('/chat/ask', [ChatController::class, 'ask'])->name('front.chat.ask');
Route::get('/chat/session/{session}', [ChatController::class, 'session'])->name('front.chat.session');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('knowledge', AdminKnowledgeController::class);
    Route::post('knowledge/{knowledge}/status', [AdminKnowledgeController::class, 'updateStatus'])->name('knowledge.status');
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('tags', TagController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('conversation-logs', [ConversationLogController::class, 'index'])->name('conversation-logs.index');
    Route::get('conversation-logs/export', [LogExportController::class, 'conversationLogs'])->name('conversation-logs.export');
    Route::get('operation-logs', [OperationLogController::class, 'index'])->name('operation-logs.index');
    Route::get('operation-logs/export', [LogExportController::class, 'operationLogs'])->name('operation-logs.export');
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});
