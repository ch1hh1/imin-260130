<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function knowledge()
    {
        return $this->hasMany(Knowledge::class, 'created_by');
    }

    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }

    public function operationLogs()
    {
        return $this->hasMany(OperationLog::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role->name ?? '', ['管理者', '編集者', '閲覧者'], true);
    }

    public function canEdit(): bool
    {
        return in_array($this->role->name ?? '', ['管理者', '編集者'], true);
    }
}
